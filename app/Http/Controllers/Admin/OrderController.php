<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\PaymentAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with(['user', 'items'])->orderByDesc('created_at')->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // For manual order entry, you may want to pass products, users, etc.
        return view('admin.orders.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'order_status' => 'required|string',
            'shipping_name' => 'required|string',
            'shipping_phone' => 'required|string',
            'shipping_address' => 'required|string',
            // Add more validation as needed
        ]);

        $order = Order::create($validated);
        // Optionally handle items, etc.
        return redirect()->route('admin.orders.index')->with('success', 'Order created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $order = Order::with(['user', 'items.product', 'statusHistories.updatedBy', 'quotedByAdmin', 'paymentAccount'])->findOrFail($id);

        $paymentAccounts = PaymentAccount::query()
            ->where('is_active', true)
            ->where('method', $order->payment_method)
            ->orderBy('account_name')
            ->get();

        return view('admin.orders.show', compact('order', 'paymentAccounts'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $order = Order::with(['user', 'items'])->findOrFail($id);
        return view('admin.orders.edit', compact('order'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $rules = [
            'order_status' => 'required|string',
            'shipping_address' => 'required|string',
            'notes' => 'nullable|string',
        ];
        // If status is shipped, require a document
        if ($request->order_status === 'shipped') {
            $rules['delivery_document'] = 'required|file|mimes:png,jpg,jpeg,pdf|max:5120';
        } else {
            $rules['delivery_document'] = 'nullable|file|mimes:png,jpg,jpeg,pdf|max:5120';
        }
        $validated = $request->validate($rules);

        // Handle file upload
        if ($request->hasFile('delivery_document')) {
            $file = $request->file('delivery_document');
            $path = $file->store('order_documents', 'public');
            $validated['delivery_document'] = $path;
        } else {
            unset($validated['delivery_document']);
        }

        $order->update($validated);
        return redirect()->route('admin.orders.show', $order->id)->with('success', 'Order updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully.');
    }

    public function updateNegotiation(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);

        $validated = $request->validate([
            'additional_transport_cost' => 'nullable|numeric|min:0',
            'additional_carrying_cost' => 'nullable|numeric|min:0',
            'bank_transfer_cost' => 'nullable|numeric|min:0',
            'additional_other_cost' => 'nullable|numeric|min:0',
            'vat_amount' => 'nullable|numeric|min:0',
            'ait_amount' => 'nullable|numeric|min:0',
            'admin_discount_amount' => 'nullable|numeric|min:0',
            'negotiated_total_amount' => 'nullable|numeric|min:0',
            'item_unit_prices' => 'nullable|array',
            'item_unit_prices.*' => 'nullable|numeric|min:0',
            'negotiation_status' => 'required|in:open,quoted,awaiting_customer_payment,proof_submitted,finalized,cancelled',
            'payment_instructions' => 'nullable|string|max:5000',
            'admin_notes' => 'nullable|string|max:5000',
            'send_chat_update' => 'nullable|boolean',
            'mark_payment_paid' => 'nullable|boolean',
            'payment_account_id' => 'nullable|integer|exists:payment_accounts,id',
        ]);

        $selectedPaymentAccountId = $validated['payment_account_id'] ?? $order->payment_account_id;

        if ($selectedPaymentAccountId) {
            $isValidForMethod = PaymentAccount::query()
                ->where('id', $selectedPaymentAccountId)
                ->where('is_active', true)
                ->where('method', $order->payment_method)
                ->exists();

            if (!$isValidForMethod) {
                return back()->withErrors([
                    'payment_account_id' => 'Selected account is not valid for this order payment method.',
                ])->withInput();
            }
        }

        if (($validated['negotiation_status'] ?? null) === 'awaiting_customer_payment' && !$selectedPaymentAccountId) {
            return back()->withErrors([
                'payment_account_id' => 'Please select a payment account before setting status to Awaiting Customer Payment.',
            ])->withInput();
        }

        $transport = (float) ($validated['additional_transport_cost'] ?? $order->additional_transport_cost ?? $order->shipping_charge ?? 0);
        $carrying = (float) ($validated['additional_carrying_cost'] ?? $order->additional_carrying_cost ?? 0);
        $transfer = (float) ($validated['bank_transfer_cost'] ?? $order->bank_transfer_cost ?? 0);
        $other = (float) ($validated['additional_other_cost'] ?? $order->additional_other_cost ?? 0);
        $discount = (float) ($validated['admin_discount_amount'] ?? $order->admin_discount_amount ?? 0);
        $vatAmount = (float) ($validated['vat_amount'] ?? $order->vat_amount ?? 0);
        $aitAmount = (float) ($validated['ait_amount'] ?? $order->ait_amount ?? $order->tax_amount ?? 0);

        DB::transaction(function () use (&$order, $validated, $transport, $carrying, $transfer, $other, $discount, $vatAmount, $aitAmount, $selectedPaymentAccountId) {
            $order->loadMissing('items');

            $unitPriceUpdates = $validated['item_unit_prices'] ?? [];
            $updatedSubtotal = 0;

            foreach ($order->items as $item) {
                $newUnitPrice = array_key_exists($item->id, $unitPriceUpdates) && $unitPriceUpdates[$item->id] !== null
                    ? (float) $unitPriceUpdates[$item->id]
                    : (float) $item->unit_price;

                $lineTotal = round($newUnitPrice * (int) $item->quantity, 2);

                if ((float) $item->unit_price !== $newUnitPrice || (float) $item->total_price !== $lineTotal) {
                    $item->update([
                        'unit_price' => $newUnitPrice,
                        'total_price' => $lineTotal,
                    ]);
                }

                $updatedSubtotal += $lineTotal;
            }

            $baseInvoiceTotal = $updatedSubtotal + $transport + $vatAmount + $aitAmount;
            $computedNegotiatedTotal = $baseInvoiceTotal + $carrying + $transfer + $other - $discount;

            $negotiatedTotal = array_key_exists('negotiated_total_amount', $validated) && $validated['negotiated_total_amount'] !== null
                ? (float) $validated['negotiated_total_amount']
                : max(0, $computedNegotiatedTotal);

            $paymentStatus = $order->payment_status;
            if (!empty($validated['mark_payment_paid'])) {
                $paymentStatus = 'paid';
                $validated['negotiation_status'] = 'finalized';
                if (in_array($order->order_status, ['pending', 'confirmed']) === false) {
                    // keep existing progressed status
                } elseif ($order->order_status === 'pending') {
                    $order->order_status = 'confirmed';
                    $order->confirmed_at = now();
                }
            }

            $order->update([
                'subtotal' => round($updatedSubtotal, 2),
                'shipping_charge' => round($transport, 2),
                'vat_amount' => round($vatAmount, 2),
                'ait_amount' => round($aitAmount, 2),
                'tax_amount' => round($vatAmount + $aitAmount, 2),
                'total_amount' => round($baseInvoiceTotal, 2),
                'additional_transport_cost' => $transport,
                'additional_carrying_cost' => $carrying,
                'bank_transfer_cost' => $transfer,
                'additional_other_cost' => $other,
                'admin_discount_amount' => $discount,
                'negotiated_total_amount' => round(max(0, $negotiatedTotal), 2),
                'negotiation_status' => $validated['negotiation_status'],
                'payment_instructions' => $validated['payment_instructions'] ?? $order->payment_instructions,
                'admin_notes' => $validated['admin_notes'] ?? $order->admin_notes,
                'payment_account_id' => $selectedPaymentAccountId,
                'quoted_by_admin_id' => Auth::id(),
                'negotiation_updated_at' => now(),
                'payment_status' => $paymentStatus,
            ]);
        });

        $order->refresh();

        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => $order->order_status,
            'previous_status' => $order->order_status,
            'notes' => 'Negotiation updated: ' . str_replace('_', ' ', $validated['negotiation_status']) . '. Final quoted total: ' . number_format((float) $order->payable_amount, 2),
            'updated_by' => Auth::id(),
            'status_date' => now(),
        ]);

        if (!empty($validated['send_chat_update'])) {
            $chat = Chat::firstOrCreate(['customer_id' => $order->user_id], ['status' => 'active']);
            if (!$chat->admin_id) {
                $chat->update(['admin_id' => Auth::id()]);
            }

            ChatMessage::create([
                'chat_id' => $chat->id,
                'user_id' => Auth::id(),
                'message' => "Order #{$order->order_number} updated. Negotiation status: {$validated['negotiation_status']}. Payable amount: ৳" . number_format((float) $order->payable_amount, 2),
            ]);
        }

        return redirect()->route('admin.orders.show', $order->id)->with('success', 'Order negotiation updated successfully.');
    }
    /**
     * Update order status via AJAX.
     */
    public function updateStatus(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);
        $request->validate([
            'order_status' => 'required|string',
            'notes' => 'nullable|string',
            'location' => 'nullable|string',
            'document' => 'nullable|file|mimes:png,jpg,jpeg,pdf|max:5120',
        ]);

        $previousStatus = $order->order_status;
        $order->order_status = $request->order_status;

        // Update timestamp fields based on status
        switch ($request->order_status) {
            case 'confirmed':
                $order->confirmed_at = now();
                break;
            case 'shipped':
                $order->shipped_at = now();
                break;
            case 'delivered':
                $order->delivered_at = now();
                break;
            case 'completed':
                $order->completed_at = now();
                break;
        }

        $order->save();

        // Handle document upload
        $documentPath = null;
        $documentName = null;
        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $documentName = $file->getClientOriginalName();
            $documentPath = $file->store('order_documents', 'public');

            // Also update order's delivery_document field if shipped/delivered
            if (in_array($request->order_status, ['shipped', 'delivered'])) {
                $order->delivery_document = $documentPath;
                $order->save();
            }
        }

        // Create status history record
        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => $request->order_status,
            'previous_status' => $previousStatus,
            'notes' => $request->notes,
            'document_path' => $documentPath,
            'document_name' => $documentName,
            'updated_by' => Auth::id(),
            'location' => $request->location,
            'status_date' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Order status updated successfully.',
            'order_status' => $order->order_status,
            'document_url' => $documentPath ? asset('storage/' . $documentPath) : null,
        ]);
    }

    /**
     * Get order tracking history
     */
    public function trackingHistory($orderId)
    {
        $order = Order::with(['statusHistories.updatedBy'])->findOrFail($orderId);

        return response()->json([
            'success' => true,
            'order' => [
                'order_number' => $order->order_number,
                'current_status' => $order->order_status,
                'histories' => $order->statusHistories->map(function ($history) {
                    return [
                        'id' => $history->id,
                        'status' => $history->status,
                        'status_display' => $history->status_display,
                        'previous_status' => $history->previous_status,
                        'notes' => $history->notes,
                        'location' => $history->location,
                        'document_path' => $history->document_path ? asset('storage/' . $history->document_path) : null,
                        'document_name' => $history->document_name,
                        'updated_by' => $history->updatedBy ? $history->updatedBy->name : 'System',
                        'status_date' => $history->status_date->format('d M Y, h:i A'),
                        'icon' => $history->status_icon,
                        'color' => $history->status_color,
                    ];
                }),
            ],
        ]);
    }

    /**
     * Handle delivery document upload via AJAX.
     */
    public function uploadDocument(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);
        $request->validate([
            'delivery_document' => 'required|file|mimes:png,jpg,jpeg,pdf|max:5120',
            'notes' => 'nullable|string',
        ]);

        if ($request->hasFile('delivery_document')) {
            $file = $request->file('delivery_document');
            $documentName = $file->getClientOriginalName();
            $path = $file->store('order_documents', 'public');

            $order->delivery_document = $path;
            $order->save();

            // Create status history record for document upload
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => $order->order_status,
                'previous_status' => $order->order_status,
                'notes' => $request->notes ?? 'Document uploaded',
                'document_path' => $path,
                'document_name' => $documentName,
                'updated_by' => Auth::id(),
                'status_date' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Document uploaded successfully.',
                'document_url' => asset('storage/' . $path),
                'document_name' => $documentName,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No document uploaded.'
        ], 422);
    }
}

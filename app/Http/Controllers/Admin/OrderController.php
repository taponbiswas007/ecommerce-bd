<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = \App\Models\Order::with(['user', 'items'])->orderByDesc('created_at')->paginate(20);
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

        $order = \App\Models\Order::create($validated);
        // Optionally handle items, etc.
        return redirect()->route('admin.orders.index')->with('success', 'Order created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $order = \App\Models\Order::with(['user', 'items.product', 'statusHistories.updatedBy'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $order = \App\Models\Order::with(['user', 'items'])->findOrFail($id);
        return view('admin.orders.edit', compact('order'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $order = \App\Models\Order::findOrFail($id);
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
        $order = \App\Models\Order::findOrFail($id);
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully.');
    }
    /**
     * Update order status via AJAX.
     */
    public function updateStatus(Request $request, $orderId)
    {
        $order = \App\Models\Order::findOrFail($orderId);
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
        \App\Models\OrderStatusHistory::create([
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
        $order = \App\Models\Order::with(['statusHistories.updatedBy'])->findOrFail($orderId);

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
        $order = \App\Models\Order::findOrFail($orderId);
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
            \App\Models\OrderStatusHistory::create([
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

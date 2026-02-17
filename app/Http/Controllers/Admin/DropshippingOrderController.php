<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DropshippingOrder;
use App\Models\DropshippingOrderItem;
use App\Models\Order;
use App\Services\CJDropshippingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class DropshippingOrderController extends Controller
{
    protected $cjService;

    public function __construct(CJDropshippingService $cjService)
    {
        $this->cjService = $cjService;
    }

    /**
     * Display a listing of dropshipping orders.
     */
    public function index(Request $request)
    {
        $query = DropshippingOrder::with(['order.user', 'items.product']);

        // Filter by status
        if ($request->has('status') && !empty($request->status)) {
            $query->where('cj_order_status', $request->status);
        }

        // Filter by date range
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter by search
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('cj_order_number', 'like', "%{$search}%")
                ->orWhereHas('order', function ($q) use ($search) {
                    $q->where('order_number', 'like', "%{$search}%");
                });
        }

        $orders = $query->orderByDesc('created_at')->paginate(20);

        // Get status statistics
        $stats = [
            'pending' => DropshippingOrder::pending()->count(),
            'confirmed' => DropshippingOrder::confirmed()->count(),
            'shipped' => DropshippingOrder::shipped()->count(),
            'total' => DropshippingOrder::count(),
            'total_profit' => DropshippingOrder::sum('profit'),
        ];

        return view('admin.dropshipping.orders.index', compact('orders', 'stats'));
    }

    /**
     * Show details of a dropshipping order.
     */
    public function show($id)
    {
        $dropshippingOrder = DropshippingOrder::with(['order.user', 'items.product'])->findOrFail($id);
        $order = $dropshippingOrder->order;

        return view('admin.dropshipping.orders.show', compact('dropshippingOrder', 'order'));
    }

    /**
     * Show form to create order on CJ
     */
    public function create()
    {
        $orders = Order::where('order_status', 'confirmed')
            ->doesntHave('dropshippingOrder')
            ->with('user', 'items.product')
            ->paginate(10);

        return view('admin.dropshipping.orders.create', compact('orders'));
    }

    /**
     * Submit order to CJ
     */
    public function submit(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        try {
            $order = Order::with('items', 'user')->findOrFail($request->order_id);

            // Check if order is already submitted to CJ
            if ($order->dropshippingOrder) {
                return redirect()->back()->with('error', 'This order is already submitted to CJ');
            }

            // Prepare order data for CJ
            $orderData = $this->prepareOrderForCJ($order);

            // Submit to CJ API
            $cjResponse = $this->cjService->createOrder($orderData);

            // Save dropshipping order
            $dropshippingOrder = new DropshippingOrder([
                'cj_order_number' => $cjResponse['orderNumber'] ?? 'CJ-' . time(),
                'cj_order_status' => 'pending',
                'cost_price' => $this->calculateOrderCost($order),
                'selling_price' => $order->total_amount,
                'profit' => $order->total_amount - $this->calculateOrderCost($order),
                'cj_response_data' => $cjResponse,
                'submitted_to_cj_at' => now(),
            ]);

            $order->dropshippingOrder()->save($dropshippingOrder);

            // Save order items
            foreach ($order->items as $item) {
                DropshippingOrderItem::create([
                    'dropshipping_order_id' => $dropshippingOrder->id,
                    'dropshipping_product_id' => $item->product_id,
                    'sku' => $item->product->sku ?? '',
                    'quantity' => $item->quantity,
                    'unit_cost_price' => $item->product->unit_price ?? 0,
                    'unit_selling_price' => $item->unit_price,
                    'total_cost_price' => ($item->product->unit_price ?? 0) * $item->quantity,
                    'total_selling_price' => $item->total_price,
                ]);
            }

            return redirect()->route('admin.dropshipping.orders.show', $dropshippingOrder->id)
                ->with('success', 'Order submitted to CJ successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Sync order status from CJ
     */
    public function syncStatus($id)
    {
        try {
            $dropshippingOrder = DropshippingOrder::findOrFail($id);

            if (!$dropshippingOrder->cj_order_number) {
                return redirect()->back()->with('error', 'No CJ order number found');
            }

            $status = $this->cjService->getOrderStatus($dropshippingOrder->cj_order_number);

            // Update order status
            $dropshippingOrder->cj_order_status = $status['status'] ?? 'pending';

            if ($status['status'] === 'confirmed') {
                $dropshippingOrder->confirmed_by_cj_at = now();
            } elseif ($status['status'] === 'shipped') {
                $dropshippingOrder->shipped_by_cj_at = now();
                $dropshippingOrder->tracking_number = $status['trackingNumber'] ?? null;
            } elseif ($status['status'] === 'delivered') {
                $dropshippingOrder->delivered_at = now();
            }

            $dropshippingOrder->cj_response_data = $status;
            $dropshippingOrder->save();

            return redirect()->back()->with('success', 'Order status updated');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Get tracking information
     */
    public function tracking($id)
    {
        try {
            $dropshippingOrder = DropshippingOrder::findOrFail($id);

            if (!$dropshippingOrder->cj_order_number) {
                return response()->json([
                    'success' => false,
                    'message' => 'No CJ order number found'
                ], 400);
            }

            $tracking = $this->cjService->getOrderTracking($dropshippingOrder->cj_order_number);

            return response()->json([
                'success' => true,
                'data' => $tracking
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Cancel order on CJ
     */
    public function cancel(Request $request, $id)
    {
        try {
            $dropshippingOrder = DropshippingOrder::findOrFail($id);

            if (!$dropshippingOrder->cj_order_number) {
                return redirect()->back()->with('error', 'No CJ order number found');
            }

            $this->cjService->cancelOrder(
                $dropshippingOrder->cj_order_number,
                $request->reason ?? 'Cancelled by admin'
            );

            $dropshippingOrder->cj_order_status = 'cancelled';
            $dropshippingOrder->notes = $request->reason ?? 'Cancelled by admin';
            $dropshippingOrder->save();

            // Update main order status
            $dropshippingOrder->order->update(['order_status' => 'cancelled']);

            return redirect()->back()->with('success', 'Order cancelled successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Prepare order data for CJ API
     */
    private function prepareOrderForCJ($order)
    {
        $products = [];
        $totalCost = 0;

        foreach ($order->items as $item) {
            // Check if it's a dropshipping product or local product
            // For simplicity, assuming OrderItem has product_id that could link to DropshippingProduct
            $costPrice = $item->product->unit_price ?? 0;

            $products[] = [
                'cj_product_id' => $item->product->cj_product_id ?? '',
                'quantity' => $item->quantity,
                'unit_cost_price' => $costPrice,
                'sku' => $item->product->sku ?? '',
            ];

            $totalCost += $costPrice * $item->quantity;
        }

        return [
            'order_number' => $order->order_number,
            'shop_name' => config('app.name'),
            'buyer_email' => $order->user->email,
            'shipping_name' => $order->shipping_name,
            'shipping_phone' => $order->shipping_phone,
            'products' => $products,
            'shipping_address' => [
                'name' => $order->shipping_name,
                'phone' => $order->shipping_phone,
                'email' => $order->shipping_email,
                'district' => $order->shipping_district,
                'upazila' => $order->shipping_upazila,
                'address' => $order->shipping_address,
            ],
        ];
    }

    /**
     * Calculate total cost of order
     */
    private function calculateOrderCost($order)
    {
        $totalCost = 0;
        foreach ($order->items as $item) {
            $unitCost = $item->product->unit_price ?? 0;
            $totalCost += $unitCost * $item->quantity;
        }
        return $totalCost;
    }

    /**
     * Bulk sync orders
     */
    public function bulkSync(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'integer|exists:dropshipping_orders,id',
        ]);

        try {
            $orders = DropshippingOrder::whereIn('id', $request->order_ids)->get();
            $updated = 0;

            foreach ($orders as $order) {
                try {
                    $status = $this->cjService->getOrderStatus($order->cj_order_number);
                    $order->cj_order_status = $status['status'] ?? 'pending';
                    $order->save();
                    $updated++;
                } catch (Exception $e) {
                    Log::error('Failed to sync order ' . $order->id . ': ' . $e->getMessage());
                }
            }

            return response()->json([
                'success' => true,
                'message' => "$updated orders synced successfully"
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}

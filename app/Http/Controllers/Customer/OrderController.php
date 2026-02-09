<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['items.product.images'])
            ->latest()
            ->paginate(10);
        return view('customer.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        // Check if order belongs to current user
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        $order->load(['items.product.images', 'statusHistories.updatedBy']);
        return view('customer.orders.show', compact('order'));
    }

    /**
     * Get order tracking information
     */
    public function tracking(Order $order)
    {
        // Check if order belongs to current user
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        $order->load(['statusHistories.updatedBy', 'items.product']);
        return view('customer.orders.tracking', compact('order'));
    }

    /**
     * Download order document
     */
    public function downloadDocument(Order $order, $historyId)
    {
        // Check if order belongs to current user
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        $history = $order->statusHistories()->findOrFail($historyId);

        if (!$history->document_path || !Storage::disk('public')->exists($history->document_path)) {
            abort(404, 'Document not found.');
        }

        return response()->download(
            Storage::disk('public')->path($history->document_path),
            $history->document_name ?? 'order_document.pdf'
        );
    }
}

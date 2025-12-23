<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Auth::user()->orders()->with(['items.product.images'])->latest()->paginate(10);
        return view('customer.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        // Check if order belongs to current user
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        $order->load(['items.product.images', 'address', 'payment']);
        return view('customer.orders.show', compact('order'));
    }
}

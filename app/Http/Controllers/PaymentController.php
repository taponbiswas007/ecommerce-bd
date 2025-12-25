<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Show payment / order summary for the given order
     */
    public function show(Order $order)
    {
        // Ensure the authenticated user owns the order
        if (Auth::id() !== $order->user_id) {
            abort(403);
        }

        return view('payment.show', compact('order'));
    }
}

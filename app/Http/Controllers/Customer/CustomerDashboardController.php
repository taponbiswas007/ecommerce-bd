<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $orders = $user->orders()->latest()->take(5)->get();
        $ordersCount = $user->orders()->count();
        $ordersTotal = $user->orders()->selectRaw('COALESCE(SUM(COALESCE(negotiated_total_amount, total_amount)),0) as total')->value('total');
        $wishlistCount = $user->wishlists()->count();
        $profile = [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone ?? '',
            'address' => $user->address ?? '',
        ];
        return view('customer.dashboard.index', compact('orders', 'ordersCount', 'ordersTotal', 'wishlistCount', 'profile'));
    }
}

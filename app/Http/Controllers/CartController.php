<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        if (Auth::check() && Auth::user()->role === 'customer') {
            $cartItems = Auth::user()->cart()->with('product.images')->get();
        } else {
            // For guests, show empty cart with login prompt
            $cartItems = collect();
        }

        return view('cart.index', compact('cartItems'));
    }

    public function add(Request $request)
    {
        // Check if user is authenticated and is a customer
        if (!Auth::check() || Auth::user()->role !== 'customer') {
            return response()->json([
                'success' => false,
                'message' => 'Please login as a customer to add items to cart',
                'requires_login' => true
            ], 401);
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);

        // Check stock availability
        if ($product->stock_quantity < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient stock. Only ' . $product->stock_quantity . ' items available.'
            ], 400);
        }

        $cartItem = Auth::user()->cart()->where('product_id', $product->id)->first();

        if ($cartItem) {
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            Auth::user()->cart()->create([
                'product_id' => $product->id,
                'quantity' => $request->quantity
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully!',
            'cart_count' => Auth::user()->cart()->count()
        ]);
    }

    // ... other methods with similar authentication checks
}

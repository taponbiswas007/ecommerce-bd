<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    // Display cart page
    public function index()
    {
        $cartItems = Cart::items();
        $subtotal = 0;
        $totalItems = 0;
        $requiresLogin = false;

        // Determine if login is required
        if (Auth::check() && Auth::user()->role !== 'customer' && $cartItems->count() > 0) {
            $requiresLogin = true; // User is logged in but not as customer
        } elseif (!Auth::check() && $cartItems->count() > 0) {
            $requiresLogin = true; // Guest has items, needs to login
        }

        // Calculate totals
        foreach ($cartItems as $item) {
            $subtotal += $item->price * $item->quantity;
            $totalItems += $item->quantity;
        }

        // Calculate shipping (example: free shipping over ৳500)
        $shipping = $subtotal >= 500 ? 0 : 60;
        $total = $subtotal + $shipping;

        return view('cart.index', compact(
            'cartItems',
            'subtotal',
            'shipping',
            'total',
            'totalItems',
            'requiresLogin'
        ));
    }

    // Add item to cart
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);

        // Check stock availability
        if ($product->stock_quantity < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient stock. Only ' . $product->stock_quantity . ' items available.',
                'stock_available' => $product->stock_quantity
            ], 400);
        }

        // Get price (discount price if available, otherwise base price)
        $price = $product->discount_price ?? $product->base_price;

        // Add to cart
        $cartItem = Cart::addItem(
            $product->id,
            $request->quantity,
            $price,
            $request->attributes ?? []
        );

        $cartCount = Cart::count();

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully!',
            'cart_count' => $cartCount,
            'cart_item' => $cartItem
        ]);
    }

    // Update cart item quantity
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0'
        ]);

        if ($request->quantity == 0) {
            // Remove item if quantity is 0
            Cart::removeItem($id);

            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart',
                'cart_count' => Cart::count()
            ]);
        }

        // Update quantity
        $updated = Cart::updateQuantity($id, $request->quantity);

        if ($updated) {
            return response()->json([
                'success' => true,
                'message' => 'Cart updated successfully',
                'cart_count' => Cart::count()
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Item not found in cart'
        ], 404);
    }

    // Remove item from cart
    public function remove($id)
    {
        Cart::removeItem($id);

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart',
            'cart_count' => Cart::count()
        ]);
    }

    // Clear entire cart
    public function clear()
    {
        Cart::clear();

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully',
            'cart_count' => 0
        ]);
    }

    // Get cart count (for AJAX updates)
    public function getCount()
    {
        $count = Cart::count();

        return response()->json([
            'success' => true,
            'cart_count' => $count
        ]);
    }
}

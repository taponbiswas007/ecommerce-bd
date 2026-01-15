<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $wishlistItems = $user->wishlists()->with('product.images')->get();

        return view('wishlist.index', compact('wishlistItems'));
    }

    public function add(Request $request, $hashid)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $product = Product::findByHashid($hashid);
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid product.'
            ], 404);
        }

        // Check if user is authenticated and customer
        if (!Auth::check() || $user->role !== 'customer') {
            return response()->json([
                'success' => false,
                'requires_login' => true,
                'message' => 'Please login to add items to wishlist'
            ]);
        }

        // Check if already in wishlist
        $existing = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Product already in wishlist'
            ]);
        }

        // Add to wishlist
        Wishlist::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product added to wishlist!',
            'wishlist_count' => $user->wishlists()->count()
        ]);
    }

    public function remove(Request $request, $hashid)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!Auth::check() || $user->role !== 'customer') {
            return response()->json([
                'success' => false,
                'requires_login' => true,
                'message' => 'Please login to manage wishlist'
            ]);
        }

        $product = Product::findByHashid($hashid);
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid product.'
            ], 404);
        }
        $wishlistItem = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if (!$wishlistItem) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found in wishlist'
            ]);
        }

        $wishlistItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item removed from wishlist',
            'wishlist_count' => $user->wishlists()->count()
        ]);
    }

    public function toggle(Request $request, $hashid)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!Auth::check() || $user->role !== 'customer') {
            return response()->json([
                'success' => false,
                'requires_login' => true,
                'message' => 'Please login to manage wishlist'
            ]);
        }

        $product = Product::findByHashid($hashid);
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid product.'
            ], 404);
        }
        $existing = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();
        if ($existing) {
            $existing->delete();
            $action = 'removed';
        } else {
            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id
            ]);
            $action = 'added';
        }

        return response()->json([
            'success' => true,
            'message' => "Product {$action} to wishlist!",
            'wishlist_count' => $user->wishlists()->count(),
            'action' => $action
        ]);
    }
}

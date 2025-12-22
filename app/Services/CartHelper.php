<?php

namespace App\Services;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CartHelper
{
    public static function getCartCount()
    {
        if (Auth::check()) {
            // For logged-in users
            return Cart::where('user_id', Auth::id())->sum('quantity');
        } else {
            // For guests (using session)
            $sessionId = session()->getId();
            return Cart::where('session_id', $sessionId)
                ->whereNull('user_id')
                ->sum('quantity');
        }
    }

    public static function getCartItems()
    {
        if (Auth::check()) {
            return Cart::with('product.images')
                ->where('user_id', Auth::id())
                ->get();
        } else {
            $sessionId = session()->getId();
            return Cart::with('product.images')
                ->where('session_id', $sessionId)
                ->whereNull('user_id')
                ->get();
        }
    }

    public static function getCartTotal()
    {
        $items = self::getCartItems();
        $total = 0;

        foreach ($items as $item) {
            $total += $item->price * $item->quantity;
        }

        return $total;
    }

    public static function mergeGuestCartWithUser($userId)
    {
        $sessionId = session()->getId();
        $guestCartItems = Cart::where('session_id', $sessionId)
            ->whereNull('user_id')
            ->get();

        foreach ($guestCartItems as $item) {
            // Check if user already has this product in cart
            $existingCartItem = Cart::where('user_id', $userId)
                ->where('product_id', $item->product_id)
                ->first();

            if ($existingCartItem) {
                // Update quantity if product exists
                $existingCartItem->quantity += $item->quantity;
                $existingCartItem->save();
                $item->delete();
            } else {
                // Move item to user's cart
                $item->user_id = $userId;
                $item->session_id = null;
                $item->save();
            }
        }
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;  // Add this import
use App\Models\User;
use App\Models\Product;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'product_id',
        'quantity',
        'price',
        'attributes',
    ];

    protected $casts = [
        'attributes' => 'array',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class)->with('images');
    }

    // Accessor for display price
    public function getDisplayPriceAttribute()
    {
        return number_format($this->price, 2);
    }

    // Accessor for total price
    public function getTotalPriceAttribute()
    {
        return number_format($this->price * $this->quantity, 2);
    }

    // Static methods for easier access
    public static function count()
    {
        if (Auth::check()) {  // Changed from auth()->check()
            return self::where('user_id', Auth::id())->sum('quantity');  // Changed from auth()->id()
        } else {
            $sessionId = session()->getId();
            return self::where('session_id', $sessionId)
                ->whereNull('user_id')
                ->sum('quantity');
        }
    }

    public static function items()
    {
        if (Auth::check()) {  // Changed
            return self::with('product.images')
                ->where('user_id', Auth::id())  // Changed
                ->get();
        } else {
            $sessionId = session()->getId();
            return self::with('product.images')
                ->where('session_id', $sessionId)
                ->whereNull('user_id')
                ->get();
        }
    }

    public static function total()
    {
        $items = self::items();
        $total = 0;

        foreach ($items as $item) {
            $total += $item->price * $item->quantity;
        }

        return $total;
    }

    public static function addItem($productId, $quantity = 1, $price = null, $attributes = [])
    {
        $product = Product::findOrFail($productId);

        if (!$price) {
            $price = $product->final_price;
        }

        if (Auth::check()) {  // Changed
            $userId = Auth::id();  // Changed
            $sessionId = null;
        } else {
            $userId = null;
            $sessionId = session()->getId();
        }

        // Check if item already exists in cart
        $cartItem = self::where('product_id', $productId)
            ->when($userId, function ($query) use ($userId) {
                return $query->where('user_id', $userId);
            })
            ->when(!$userId, function ($query) use ($sessionId) {
                return $query->where('session_id', $sessionId)
                    ->whereNull('user_id');
            })
            ->first();

        if ($cartItem) {
            // Update quantity if item exists
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            // Create new cart item
            $cartItem = self::create([
                'user_id' => $userId,
                'session_id' => $sessionId,
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $price,
                'attributes' => $attributes,
            ]);
        }

        return $cartItem;
    }

    public static function removeItem($cartId)
    {
        if (Auth::check()) {  // Changed
            return self::where('id', $cartId)
                ->where('user_id', Auth::id())  // Changed
                ->delete();
        } else {
            $sessionId = session()->getId();
            return self::where('id', $cartId)
                ->where('session_id', $sessionId)
                ->whereNull('user_id')
                ->delete();
        }
    }

    public static function updateQuantity($cartId, $quantity)
    {
        if ($quantity <= 0) {
            return self::removeItem($cartId);
        }

        if (Auth::check()) {  // Changed
            return self::where('id', $cartId)
                ->where('user_id', Auth::id())  // Changed
                ->update(['quantity' => $quantity]);
        } else {
            $sessionId = session()->getId();
            return self::where('id', $cartId)
                ->where('session_id', $sessionId)
                ->whereNull('user_id')
                ->update(['quantity' => $quantity]);
        }
    }

    public static function clear()
    {
        if (Auth::check()) {  // Changed
            return self::where('user_id', Auth::id())->delete();  // Changed
        } else {
            $sessionId = session()->getId();
            return self::where('session_id', $sessionId)
                ->whereNull('user_id')
                ->delete();
        }
    }

    public static function mergeGuestCart()
    {
        if (!Auth::check()) {  // Changed
            return;
        }

        $userId = Auth::id();  // Changed
        $sessionId = session()->getId();

        $guestCartItems = self::where('session_id', $sessionId)
            ->whereNull('user_id')
            ->get();

        foreach ($guestCartItems as $item) {
            // Check if user already has this product in cart
            $existingCartItem = self::where('user_id', $userId)
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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        return $this->belongsTo(Product::class);
    }

    // Static methods for easier access
    public static function count()
    {
        if (auth()->check()) {
            return self::where('user_id', auth()->id())->sum('quantity');
        } else {
            $sessionId = session()->getId();
            return self::where('session_id', $sessionId)
                ->whereNull('user_id')
                ->sum('quantity');
        }
    }

    public static function items()
    {
        if (auth()->check()) {
            return self::with('product.images')
                ->where('user_id', auth()->id())
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
            $price = $product->display_price;
        }

        if (auth()->check()) {
            $userId = auth()->id();
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
        if (auth()->check()) {
            return self::where('id', $cartId)
                ->where('user_id', auth()->id())
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

        if (auth()->check()) {
            return self::where('id', $cartId)
                ->where('user_id', auth()->id())
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
        if (auth()->check()) {
            return self::where('user_id', auth()->id())->delete();
        } else {
            $sessionId = session()->getId();
            return self::where('session_id', $sessionId)
                ->whereNull('user_id')
                ->delete();
        }
    }

    public static function mergeGuestCart()
    {
        if (!auth()->check()) {
            return;
        }

        $userId = auth()->id();
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

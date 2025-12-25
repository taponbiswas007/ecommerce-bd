<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Product;
use App\Models\Coupon;
use App\Models\DeliveryCharge;

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

    // Static method to calculate price for product and quantity
    public static function calculatePrice($product, $quantity)
    {
        $bulkPrice = $product->getPriceForQuantity($quantity);
        return $bulkPrice ? $bulkPrice->price : $product->final_price;
    }

    // Static methods for easier access
    public static function count()
    {
        if (Auth::check()) {
            return self::where('user_id', Auth::id())->sum('quantity');
        } else {
            $sessionId = session()->getId();
            return self::where('session_id', $sessionId)
                ->whereNull('user_id')
                ->sum('quantity');
        }
    }

    public static function items()
    {
        if (Auth::check()) {
            return self::with('product.images')
                ->where('user_id', Auth::id())
                ->get();
        } else {
            $sessionId = session()->getId();
            return self::with('product.images')
                ->where('session_id', $sessionId)
                ->whereNull('user_id')
                ->get();
        }
    }

    public static function subtotal()
    {
        $items = self::items();
        $total = 0;
        foreach ($items as $item) {
            $total += $item->price * $item->quantity;
        }
        return $total;
    }

    public static function discount($couponCode = null)
    {
        $subtotal = self::subtotal();
        if (!$couponCode) {
            return 0;
        }
        $coupon = Coupon::where('code', $couponCode)->where('is_active', true)->first();
        if (!$coupon) {
            return 0;
        }
        // Check validity
        if (now()->lt($coupon->valid_from) || now()->gt($coupon->valid_to)) {
            return 0;
        }
        if ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) {
            return 0;
        }
        if ($coupon->min_order_amount && $subtotal < $coupon->min_order_amount) {
            return 0;
        }
        $discount = 0;
        if ($coupon->discount_type == 'percentage') {
            $discount = ($subtotal * $coupon->discount_value) / 100;
            if ($coupon->max_discount_amount && $discount > $coupon->max_discount_amount) {
                $discount = $coupon->max_discount_amount;
            }
        } else {
            $discount = $coupon->discount_value;
        }
        return $discount;
    }

    public static function tax($subtotal = null)
    {
        if ($subtotal === null) {
            $subtotal = self::subtotal();
        }
        // Assume 5% tax; adjust as needed
        return $subtotal * 0.05;
    }

    public static function shipping($district = null, $upazila = null, $transportCompanyId = null, $method = 'transport')
    {
        // If destination missing, fallback to simple rule
        if (!$district || !$upazila) {
            $subtotal = self::subtotal();
            return $subtotal >= 500 ? 0 : 60;
        }

        $items = self::items();

        // Use the ShippingCalculator service
        try {
            $calc = new \App\Services\ShippingCalculator();
            $res = $calc->calculate($items, $district, $upazila, $transportCompanyId, $method);
            if (isset($res['total'])) {
                return (float) $res['total'];
            }
        } catch (\Throwable $e) {
            // In case of failure, fallback to legacy rule
            report($e);
        }

        // Legacy fallback
        $chargeRecord = DeliveryCharge::where('district', $district)
            ->where('upazila', $upazila)
            ->where('is_active', true)
            ->first();

        $perItemTransport = $chargeRecord ? (float) $chargeRecord->charge : (float) config('shipping.default_transport_per_item', 80);
        $qty = self::count();
        $shopToTransportPerItem = (float) config('shipping.shop_to_transport_per_item', 50);
        $cost = ($shopToTransportPerItem + $perItemTransport) * max(1, $qty);
        return round($cost, 2);
    }

    public static function grandTotal($couponCode = null, $district = null, $upazila = null)
    {
        $subtotal = self::subtotal();
        $discount = self::discount($couponCode);
        $tax = self::tax($subtotal - $discount);
        $shipping = self::shipping($district, $upazila);
        return $subtotal - $discount + $tax + $shipping;
    }

    public static function addItem($productId, $quantity = 1, $price = null, $attributes = [])
    {
        $product = Product::findOrFail($productId);

        if (!$price) {
            $price = self::calculatePrice($product, $quantity);
        }

        if (Auth::check()) {
            $userId = Auth::id();
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
            // Update quantity and recalculate price
            $cartItem->quantity += $quantity;
            $cartItem->price = self::calculatePrice($product, $cartItem->quantity);
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
        if (Auth::check()) {
            return self::where('id', $cartId)
                ->where('user_id', Auth::id())
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

        if (Auth::check()) {
            $updated = self::where('id', $cartId)
                ->where('user_id', Auth::id())
                ->update(['quantity' => $quantity]);
        } else {
            $sessionId = session()->getId();
            $updated = self::where('id', $cartId)
                ->where('session_id', $sessionId)
                ->whereNull('user_id')
                ->update(['quantity' => $quantity]);
        }

        if ($updated) {
            // Recalculate price
            $cartItem = self::find($cartId);
            if ($cartItem) {
                $cartItem->price = self::calculatePrice($cartItem->product, $cartItem->quantity);
                $cartItem->save();
            }
        }

        return $updated;
    }

    public static function clear()
    {
        if (Auth::check()) {
            return self::where('user_id', Auth::id())->delete();
        } else {
            $sessionId = session()->getId();
            return self::where('session_id', $sessionId)
                ->whereNull('user_id')
                ->delete();
        }
    }

    public static function mergeGuestCart()
    {
        if (!Auth::check()) {
            return;
        }

        $userId = Auth::id();
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
                // Update quantity and price
                $existingCartItem->quantity += $item->quantity;
                $existingCartItem->price = self::calculatePrice($existingCartItem->product, $existingCartItem->quantity);
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

<?php

namespace App\Http\Controllers;

use App\Models\DropshippingProduct;
use App\Models\Product;
use App\Models\Cart;
use App\Services\DropshippingLocalProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::items();
        $subtotal = Cart::subtotal();
        $totalItems = Cart::count();
        $requiresLogin = (!Auth::check() || Auth::user()->role !== 'customer');

        // Get coupon from session (if applied)
        $couponCode = session('coupon_code');
        $discount = Cart::discount($couponCode);

        // Get shipping based on user location
        $district = Auth::check() ? Auth::user()->district : null;
        $upazila = Auth::check() ? Auth::user()->upazila : null;
        $shipping = Cart::shipping($district, $upazila);

        // Calculate tax on discounted subtotal
        $taxSummary = Cart::taxSummary($discount);
        $tax = $taxSummary['total_tax'];

        // Grand total
        $total = Cart::grandTotal($couponCode, $district, $upazila);

        return view('cart.index', compact(
            'cartItems',
            'subtotal',
            'discount',
            'tax',
            'taxSummary',
            'shipping',
            'total',
            'totalItems',
            'requiresLogin',
            'couponCode'
        ));
    }

    // Add item to cart
    public function add(Request $request)
    {
        // Log attributes received from frontend
        $attributes = $request->input('attributes', []);
        if ($attributes instanceof \Symfony\Component\HttpFoundation\ParameterBag) {
            $attributes = $attributes->all();
        }
        if (!is_array($attributes)) {
            $attributes = [];
        }
        $dropshippingId = $request->input('dropshipping_product_id');
        $product = null;

        if ($dropshippingId) {
            $dropshippingProduct = DropshippingProduct::find($dropshippingId);
            if (!$dropshippingProduct) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid dropshipping product.'
                ], 404);
            }

            try {
                $product = (new DropshippingLocalProductService())->ensureLocalProduct($dropshippingProduct);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 400);
            }
        } else {
            $product = Product::findByHashid($request->product_id);
        }

        $attributePairs = $product?->attribute_pairs ?? [];
        // Only allow logged-in users to add to cart
        if (!Auth::check() || Auth::user()->role !== 'customer') {
            return response()->json([
                'success' => false,
                'requires_login' => true,
                'message' => 'Please login to add items to cart.'
            ], 401);
        }


        $request->validate([
            'product_id' => 'nullable',
            'dropshipping_product_id' => 'nullable',
            'quantity' => 'required|integer|min:1'
        ]);

        if (!$dropshippingId && !$request->product_id) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid product.'
            ], 404);
        }

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid product.'
            ], 404);
        }

        // Backend attribute selection enforcement
        $attributePairs = $product->attribute_pairs ?? [];
        if (!empty($attributePairs)) {
            $selected = $request->input('attributes', []);
            if (!is_array($selected)) {
                $selected = [];
            }
            $missing = [];
            foreach (array_keys($attributePairs) as $key) {
                if (empty($selected[$key])) {
                    $missing[] = $key;
                }
            }
            if (!empty($missing)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please select: ' . implode(', ', array_map(function ($k) {
                        return str_replace('_', ' ', $k);
                    }, $missing))
                ], 422);
            }
        }

        // Check stock availability
        if ($product->stock_quantity < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient stock. Only ' . $product->stock_quantity . ' items available.',
                'stock_available' => $product->stock_quantity
            ], 400);
        }

        // Add to cart (price calculated automatically)
        $cartItem = Cart::addItem(
            $product->id,
            $request->quantity,
            null, // Price will be calculated
            $attributes
        );

        $cartCount = Cart::count();

        if (isset($cartItem->already_exists) && $cartItem->already_exists) {
            return response()->json([
                'success' => true,
                'message' => 'Product quantity updated in cart!',
                'cart_count' => $cartCount,
                'cart_item' => $cartItem
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully!',
            'cart_count' => $cartCount,
            'cart_item' => $cartItem
        ]);
    }


    // Update cart item quantity
    public function update(Request $request, $hashid)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0'
        ]);

        $decoded = app('hashids')->decode($hashid);
        if (count($decoded) === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid cart item.'
            ], 404);
        }
        $id = $decoded[0];
        if ($request->quantity == 0) {
            // Remove item if quantity is 0
            Cart::removeItem($id);
            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart',
                'cart_count' => Cart::count()
            ]);
        }
        // Update quantity (price recalculated automatically)
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
    public function remove($hashid)
    {
        $decoded = app('hashids')->decode($hashid);
        if (count($decoded) === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid cart item.'
            ], 404);
        }
        $id = $decoded[0];
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

    // Apply coupon
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string'
        ]);

        $couponCode = strtoupper($request->coupon_code);
        $discount = Cart::discount($couponCode);

        if ($discount > 0) {
            session(['coupon_code' => $couponCode]);
            return response()->json([
                'success' => true,
                'message' => 'Coupon applied successfully!',
                'discount' => $discount
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid or expired coupon code.'
        ], 400);
    }

    // Remove coupon
    public function removeCoupon()
    {
        session()->forget('coupon_code');

        return response()->json([
            'success' => true,
            'message' => 'Coupon removed successfully!'
        ]);
    }

    // Get cart data as JSON for offcanvas
    public function getCartData()
    {
        $cartItems = Cart::items();
        $subtotal = Cart::subtotal();
        $totalItems = Cart::count();

        // Get coupon from session (if applied)
        $couponCode = session('coupon_code');
        $discount = Cart::discount($couponCode);

        // Get shipping based on user location
        $district = Auth::check() ? Auth::user()->district : null;
        $upazila = Auth::check() ? Auth::user()->upazila : null;
        $shipping = Cart::shipping($district, $upazila);

        // Calculate tax
        $taxSummary = Cart::taxSummary($discount);
        $tax = $taxSummary['total_tax'];

        // Grand total
        $total = Cart::grandTotal($couponCode, $district, $upazila);

        // Format cart items for JSON response
        $items = $cartItems->map(function ($item) {
            // Get product image from images relationship
            $productImage = null;
            if ($item->product) {
                $primaryImage = $item->product->primaryImage;
                if ($primaryImage && $primaryImage->image_path) {
                    $productImage = asset('storage/' . $primaryImage->image_path);
                } else {
                    $firstImage = $item->product->images()->first();
                    if ($firstImage && $firstImage->image_path) {
                        $productImage = asset('storage/' . $firstImage->image_path);
                    }
                }
            }

            // Fallback to default image
            if (!$productImage) {
                $productImage = asset('assets/images/no-image.png');
            }

            return [
                'id' => $item->id,
                'hashid' => app('hashids')->encode($item->id),
                'product_id' => $item->product_id,
                'product_name' => $item->product->name ?? 'Product',
                'product_slug' => $item->product->slug ?? '',
                'quantity' => $item->quantity,
                'price' => $item->price,
                'total' => $item->price * $item->quantity,
                'image' => $productImage,
                'attributes' => $item->attributes ?? []
            ];
        });

        return response()->json([
            'success' => true,
            'items' => $items,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax' => $tax,
            'shipping' => $shipping,
            'total' => $total,
            'cart_count' => $totalItems
        ]);
    }

    /**
     * Map dropshipping product to local product (for quick view)
     */
    public function mapDropshippingToLocal(Request $request)
    {
        $request->validate([
            'dropshipping_product_id' => 'required|integer'
        ]);

        $dropshippingProduct = DropshippingProduct::find($request->dropshipping_product_id);

        if (!$dropshippingProduct) {
            return response()->json([
                'success' => false,
                'message' => 'Dropshipping product not found.'
            ], 404);
        }

        try {
            $localProduct = (new DropshippingLocalProductService())->ensureLocalProduct($dropshippingProduct);

            return response()->json([
                'success' => true,
                'product_hashid' => $localProduct->hashid,
                'message' => 'Product mapped successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}

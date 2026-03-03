<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Coupon;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\OrderStatusHistory;
use App\Models\User;
use App\Mail\NewOrderRequestMail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    /**
     * Show checkout form
     */
    public function index()
    {
        $cartItems = Cart::items();
        $subtotal = Cart::subtotal();
        $couponCode = session('coupon_code');
        $discount = Cart::discount($couponCode);
        $district = Auth::check() ? Auth::user()->district : null;
        $upazila = Auth::check() ? Auth::user()->upazila : null;

        // Use config locations directly
        $districts = array_keys(config('locations', []));

        // Shipping amount (legacy numeric) — also compute detailed estimate using ShippingCalculator
        $shipping = Cart::shipping($district, $upazila);
        try {
            $detailed = (new \App\Services\ShippingCalculator())->calculate($cartItems, $district, $upazila, null, 'transport');
        } catch (\Throwable $e) {
            $detailed = null;
        }
        $taxSummary = Cart::taxSummary($discount);
        $tax = $taxSummary['total_tax'];
        $total = Cart::grandTotal($couponCode, $district, $upazila, null, 'transport');

        // Transport companies from DB (optional)
        $transports = \App\Models\TransportCompany::where('is_active', true)->pluck('name', 'id')->toArray();

        // Offer shipping methods
        $shippingMethods = [
            'transport' => 'Transport Delivery',
            'own' => 'Own/Manual Delivery (To be confirmed)',
            'pickup' => 'Pickup from Store',
        ];

        return view('checkout.index', compact(
            'cartItems',
            'subtotal',
            'discount',
            'tax',
            'taxSummary',
            'shipping',
            'total',
            'couponCode',
            'districts',
            'transports',
            'shippingMethods'
        ));
    }

    /**
     * Process checkout and create order
     */
    public function process(Request $request)
    {
        $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_phone' => 'required|string|max:30',
            'shipping_email' => 'nullable|email|max:255',
            'shipping_district' => 'required|string',
            'shipping_upazila' => 'required|string',
            'shipping_address' => 'required|string',
            'shipping_method' => 'required|in:transport,own,pickup',
            'transport_company_id' => 'nullable|exists:transport_companies,id',
            'transport_name' => 'nullable|string|max:255',
            'payment_method' => 'required|in:bkash,rocket,bank_transfer',
            'terms_accepted' => 'accepted',
        ]);


        // Update user info if changed
        $user = User::find(Auth::id());
        $userChanged = false;
        if ($user) {
            if ($user->name !== $request->shipping_name) {
                $user->name = $request->shipping_name;
                $userChanged = true;
            }
            if ($user->phone !== $request->shipping_phone) {
                $user->phone = $request->shipping_phone;
                $userChanged = true;
            }
            if ($request->shipping_email && $user->email !== $request->shipping_email) {
                $user->email = $request->shipping_email;
                $userChanged = true;
            }
            if ($user->district !== $request->shipping_district) {
                $user->district = $request->shipping_district;
                $userChanged = true;
            }
            if ($user->upazila !== $request->shipping_upazila) {
                $user->upazila = $request->shipping_upazila;
                $userChanged = true;
            }
            if ($user->address !== $request->shipping_address) {
                $user->address = $request->shipping_address;
                $userChanged = true;
            }
            if ($userChanged) {
                $user->save();
            }
        }

        $cartItems = Cart::items();
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $subtotal = Cart::subtotal();
        $couponCode = session('coupon_code');
        $discount = Cart::discount($couponCode);
        $taxSummary = Cart::taxSummary($discount);
        $tax = $taxSummary['total_tax'];
        $vatAmount = $taxSummary['vat_amount'] ?? 0;
        $aitAmount = $taxSummary['ait_amount'] ?? 0;
        $shipping = Cart::shipping($request->shipping_district, $request->shipping_upazila, $request->transport_company_id ?? null, $request->shipping_method ?? 'transport');
        $total = Cart::grandTotal(
            $couponCode,
            $request->shipping_district,
            $request->shipping_upazila,
            $request->transport_company_id ?? null,
            $request->shipping_method ?? 'transport'
        );

        $order = Order::create([
            'order_number' => strtoupper('ORD-' . Str::random(10)),
            'user_id' => Auth::id(),
            'subtotal' => $subtotal,
            'discount_amount' => $discount,
            'shipping_charge' => $shipping,
            'tax_amount' => $tax,
            'vat_amount' => $vatAmount,
            'ait_amount' => $aitAmount,
            'total_amount' => $total,
            'payment_method' => $request->payment_method,
            'payment_status' => 'pending',
            'order_status' => 'pending',
            'negotiation_status' => 'open',
            'shipping_name' => $request->shipping_name,
            'shipping_phone' => $request->shipping_phone,
            'shipping_email' => $request->shipping_email,
            'shipping_district' => $request->shipping_district,
            'shipping_upazila' => $request->shipping_upazila,
            'shipping_address' => $request->shipping_address,
            'transport_name' => $request->transport_name ?? null,
            'transport_company_id' => $request->transport_company_id ?? null,
            'shipping_method' => $request->shipping_method ?? 'transport',
            'terms_accepted' => $request->has('terms_accepted'),
            'customer_notes' => $request->customer_notes ?? null,
            'is_self_delivery_risk' => ($request->shipping_method === 'pickup'),
            'negotiation_updated_at' => now(),
        ]);

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'product_name' => $item->product->name ?? null,
                'unit_price' => $item->price,
                'quantity' => $item->quantity,
                'total_price' => $item->price * $item->quantity,
                'attributes' => $item->attributes ?? [],
            ]);

            // Reduce stock if product has stock_quantity
            if ($item->product && isset($item->product->stock_quantity)) {
                $product = $item->product;
                $product->stock_quantity = max(0, $product->stock_quantity - $item->quantity);
                $product->save();
            }
        }

        // Increment coupon usage
        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->first();
            if ($coupon) {
                $coupon->used_count = ($coupon->used_count ?? 0) + 1;
                $coupon->save();
            }
            session()->forget('coupon_code');
        }

        // Clear cart
        Cart::clear();

        $this->createInitialNegotiationContext($order);
        $this->notifyAdminsForNewOrder($order);

        return redirect()->route('payment.show', $order->id)->with('success', 'Order request submitted. Admin will share final quote soon.');
    }

    /**
     * Return shipping estimate JSON for the checkout page
     */
    public function estimate(Request $request)
    {
        $district = $request->query('district');
        $upazila = $request->query('upazila');
        $transportCompanyId = $request->query('transport_company_id');
        $method = $request->query('method', 'transport');

        $items = \App\Models\Cart::items();
        try {
            $res = (new \App\Services\ShippingCalculator())->calculate($items, $district, $upazila, $transportCompanyId, $method);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Estimate failed'], 500);
        }

        return response()->json($res);
    }

    private function createInitialNegotiationContext(Order $order): void
    {
        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => 'pending',
            'previous_status' => null,
            'notes' => 'Order request received. Price and logistics costs will be finalized after admin negotiation.',
            'updated_by' => null,
            'status_date' => now(),
        ]);

        $chat = Chat::firstOrCreate(
            ['customer_id' => $order->user_id],
            ['status' => 'active']
        );

        $admin = User::where('role', 'admin')->orderBy('id')->first();
        if ($admin && !$chat->admin_id) {
            $chat->update(['admin_id' => $admin->id]);
        }

        if ($admin) {
            ChatMessage::create([
                'chat_id' => $chat->id,
                'user_id' => $admin->id,
                'message' => "Order #{$order->order_number} request received. We will confirm transport, carrying and transfer costs shortly.",
            ]);
        }
    }

    private function notifyAdminsForNewOrder(Order $order): void
    {
        $order->loadMissing(['user', 'items']);

        $emails = User::where('role', 'admin')
            ->whereNotNull('email')
            ->pluck('email')
            ->unique()
            ->values();

        if ($emails->isEmpty()) {
            return;
        }

        Mail::to($emails->all())->send(new NewOrderRequestMail($order));
    }
}

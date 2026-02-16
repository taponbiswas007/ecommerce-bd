<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coupons = Coupon::orderByDesc('created_at')->paginate(20);

        return view('admin.coupons.index', compact('coupons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.coupons.create');
    }

    /**
     * Generate a unique coupon code.
     */
    public function generateCode()
    {
        $code = $this->generateUniqueCode();

        return response()->json([
            'success' => true,
            'code' => $code,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0.01',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'valid_from' => 'required|date',
            'valid_to' => 'required|date|after_or_equal:valid_from',
            'usage_limit' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'for_new_users_only' => 'boolean',
        ]);

        if ($request->discount_type === 'percentage' && $request->discount_value > 100) {
            return redirect()->back()
                ->withErrors(['discount_value' => 'Percentage discount cannot exceed 100.'])
                ->withInput();
        }

        try {
            $coupon = new Coupon();
            $coupon->code = strtoupper(trim($request->code));
            $coupon->name = $request->name;
            $coupon->description = $request->description;
            $coupon->discount_type = $request->discount_type;
            $coupon->discount_value = $request->discount_value;
            $coupon->min_order_amount = $request->min_order_amount;
            $coupon->max_discount_amount = $request->max_discount_amount;
            $coupon->valid_from = $request->valid_from;
            $coupon->valid_to = $request->valid_to;
            $coupon->usage_limit = $request->usage_limit;
            $coupon->used_count = 0;
            $coupon->is_active = $request->boolean('is_active');
            $coupon->for_new_users_only = $request->boolean('for_new_users_only');
            $coupon->save();

            return redirect()->route('admin.coupons.index')->with('success', 'Coupon created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Coupon $coupon)
    {
        return view('admin.coupons.show', compact('coupon'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Coupon $coupon)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0.01',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'valid_from' => 'required|date',
            'valid_to' => 'required|date|after_or_equal:valid_from',
            'usage_limit' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'for_new_users_only' => 'boolean',
        ]);

        if ($request->discount_type === 'percentage' && $request->discount_value > 100) {
            return redirect()->back()
                ->withErrors(['discount_value' => 'Percentage discount cannot exceed 100.'])
                ->withInput();
        }

        try {
            $coupon->code = strtoupper(trim($request->code));
            $coupon->name = $request->name;
            $coupon->description = $request->description;
            $coupon->discount_type = $request->discount_type;
            $coupon->discount_value = $request->discount_value;
            $coupon->min_order_amount = $request->min_order_amount;
            $coupon->max_discount_amount = $request->max_discount_amount;
            $coupon->valid_from = $request->valid_from;
            $coupon->valid_to = $request->valid_to;
            $coupon->usage_limit = $request->usage_limit;
            $coupon->is_active = $request->boolean('is_active');
            $coupon->for_new_users_only = $request->boolean('for_new_users_only');
            $coupon->save();

            return redirect()->route('admin.coupons.index')->with('success', 'Coupon updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Coupon $coupon)
    {
        try {
            $coupon->delete();

            return redirect()->route('admin.coupons.index')->with('success', 'Coupon deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    private function generateUniqueCode(int $length = 8): string
    {
        do {
            $code = Str::upper(Str::random($length));
        } while (Coupon::where('code', $code)->exists());

        return $code;
    }
}

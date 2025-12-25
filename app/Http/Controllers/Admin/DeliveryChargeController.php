<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DeliveryChargeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $deliveryCharges = \App\Models\DeliveryCharge::orderBy('district')->paginate(25);

        return view('admin.delivery-charges.index', compact('deliveryCharges'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $districts = array_keys(config('locations'));
        return view('admin.delivery-charges.create', compact('districts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'district' => 'required|string',
            'upazila' => 'required|string',
            'charge' => 'required|numeric|min:0',
            'estimated_days' => 'nullable|integer|min:0',
        ]);

        \App\Models\DeliveryCharge::create([
            'district' => $request->district,
            'upazila' => $request->upazila,
            'charge' => $request->charge,
            'estimated_days' => $request->estimated_days,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.delivery-charges.index')->with('success', 'Delivery charge created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $deliveryCharge = \App\Models\DeliveryCharge::findOrFail($id);
        return view('admin.delivery-charges.show', compact('deliveryCharge'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $deliveryCharge = \App\Models\DeliveryCharge::findOrFail($id);
        $districts = array_keys(config('locations'));
        return view('admin.delivery-charges.edit', compact('deliveryCharge', 'districts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'district' => 'required|string',
            'upazila' => 'required|string',
            'charge' => 'required|numeric|min:0',
            'estimated_days' => 'nullable|integer|min:0',
        ]);

        $deliveryCharge = \App\Models\DeliveryCharge::findOrFail($id);
        $deliveryCharge->update([
            'district' => $request->district,
            'upazila' => $request->upazila,
            'charge' => $request->charge,
            'estimated_days' => $request->estimated_days,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.delivery-charges.index')->with('success', 'Delivery charge updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deliveryCharge = \App\Models\DeliveryCharge::findOrFail($id);
        $deliveryCharge->delete();

        return redirect()->route('admin.delivery-charges.index')->with('success', 'Delivery charge deleted.');
    }

    /**
     * Return upazilas for a given district (AJAX)
     */
    public function upazilas(Request $request)
    {
        $district = $request->query('district');

        if (!$district) {
            return response()->json([]);
        }

        // Prefer DB entries (distinct upazilas for this district)
        $dbUpazilas = \App\Models\DeliveryCharge::where('district', $district)
            ->where('is_active', true)
            ->distinct()
            ->pluck('upazila')
            ->filter()
            ->values()
            ->toArray();

        if (!empty($dbUpazilas)) {
            return response()->json($dbUpazilas);
        }

        // Fallback to config locations
        $data = config('locations');
        $upazilas = $data[$district] ?? [];

        return response()->json($upazilas);
    }
}

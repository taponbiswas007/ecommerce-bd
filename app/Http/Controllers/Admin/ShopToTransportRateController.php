<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShopToTransportRate;

class ShopToTransportRateController extends Controller
{
    public function index()
    {
        $rates = ShopToTransportRate::paginate(25);
        $districts = array_keys(config('locations'));
        return view('admin.shop-to-transport-rates.index', compact('rates', 'districts'));
    }

    public function create()
    {
        $districts = array_keys(config('locations'));
        $packageTypes = \App\Models\PackagingRule::where('is_active', true)
            ->pluck('unit_name')
            ->unique()
            ->values();
        return view('admin.shop-to-transport-rates.create', compact('districts', 'packageTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'package_type' => 'required|string',
            'rate' => 'required|numeric|min:0',
        ]);

        ShopToTransportRate::create([
            'package_type' => $request->package_type,
            'district' => $request->district ?? null,
            'upazila' => $request->upazila ?? null,
            'rate' => $request->rate,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);
        return redirect()->route('admin.shop-to-transport-rates.index')->with('success', 'Saved');
    }

    public function edit(ShopToTransportRate $shopToTransportRate)
    {
        $districts = array_keys(config('locations'));
        return view('admin.shop-to-transport-rates.edit', compact('shopToTransportRate', 'districts'));
    }

    public function update(Request $request, ShopToTransportRate $shopToTransportRate)
    {
        $request->validate([
            'package_type' => 'required|string',
            'rate' => 'required|numeric|min:0',
        ]);

        $shopToTransportRate->update([
            'package_type' => $request->package_type,
            'district' => $request->district ?? null,
            'upazila' => $request->upazila ?? null,
            'rate' => $request->rate,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);
        return redirect()->route('admin.shop-to-transport-rates.index')->with('success', 'Updated');
    }

    public function destroy(ShopToTransportRate $shopToTransportRate)
    {
        $shopToTransportRate->delete();
        return redirect()->route('admin.shop-to-transport-rates.index')->with('success', 'Deleted');
    }

    /**
     * AJAX endpoint for getting upazilas by district
     */
    public function upazilas(Request $request)
    {
        $district = $request->query('district');
        if (!$district) {
            return response()->json([]);
        }
        $data = config('locations');
        $upazilas = $data[$district] ?? [];
        return response()->json($upazilas);
    }
}

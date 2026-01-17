<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PackageRate;
use App\Models\TransportCompany;

class PackageRateController extends Controller
{
    public function index()
    {
        $rates = PackageRate::with('transportCompany')->paginate(25);
        return view('admin.package-rates.index', compact('rates'));
    }

    public function create()
    {
        $companies = TransportCompany::where('is_active', true)->pluck('name', 'id');
        $packageTypes = \App\Models\PackagingRule::where('is_active', true)
            ->pluck('unit_name')
            ->unique()
            ->values();
        return view('admin.package-rates.create', compact('companies', 'packageTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'transport_company_id' => 'required|exists:transport_companies,id',
            'package_type' => 'required|string',
            'rate' => 'required|numeric|min:0',
        ]);

        PackageRate::create([
            'transport_company_id' => $request->transport_company_id,
            'package_type' => $request->package_type,
            'district' => $request->district ?? null,
            'upazila' => $request->upazila ?? null,
            'rate' => $request->rate,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);
        return redirect()->route('admin.package-rates.index')->with('success', 'Saved');
    }

    public function edit(PackageRate $packageRate)
    {
        $companies = TransportCompany::where('is_active', true)->pluck('name', 'id');
        return view('admin.package-rates.edit', compact('packageRate', 'companies'));
    }

    public function update(Request $request, PackageRate $packageRate)
    {
        $request->validate([
            'transport_company_id' => 'required|exists:transport_companies,id',
            'package_type' => 'required|string',
            'rate' => 'required|numeric|min:0',
        ]);
        $packageRate->update([
            'transport_company_id' => $request->transport_company_id,
            'package_type' => $request->package_type,
            'district' => $request->district ?? null,
            'upazila' => $request->upazila ?? null,
            'rate' => $request->rate,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);
        return redirect()->route('admin.package-rates.index')->with('success', 'Updated');
    }

    public function destroy(PackageRate $packageRate)
    {
        $packageRate->delete();
        return redirect()->route('admin.package-rates.index')->with('success', 'Deleted');
    }
}

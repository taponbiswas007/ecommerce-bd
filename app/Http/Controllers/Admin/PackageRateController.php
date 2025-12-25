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
        return view('admin.package-rates.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'transport_company_id' => 'required|exists:transport_companies,id',
            'package_type' => 'required|string',
            'rate' => 'required|numeric|min:0',
        ]);

        PackageRate::create($request->only(['transport_company_id', 'package_type', 'district', 'upazila', 'rate', 'is_active']));
        return redirect()->route('admin.package-rates.index')->with('success', 'Saved');
    }

    public function edit(PackageRate $packageRate)
    {
        $companies = TransportCompany::where('is_active', true)->pluck('name', 'id');
        return view('admin.package-rates.edit', ['rate' => $packageRate, 'companies' => $companies]);
    }

    public function update(Request $request, PackageRate $packageRate)
    {
        $request->validate([
            'transport_company_id' => 'required|exists:transport_companies,id',
            'package_type' => 'required|string',
            'rate' => 'required|numeric|min:0',
        ]);
        $packageRate->update($request->only(['transport_company_id', 'package_type', 'district', 'upazila', 'rate', 'is_active']));
        return redirect()->route('admin.package-rates.index')->with('success', 'Updated');
    }

    public function destroy(PackageRate $packageRate)
    {
        $packageRate->delete();
        return redirect()->route('admin.package-rates.index')->with('success', 'Deleted');
    }
}

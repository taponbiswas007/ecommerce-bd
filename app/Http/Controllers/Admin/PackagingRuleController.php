<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PackagingRule;
use App\Models\Product;

class PackagingRuleController extends Controller
{
    public function index()
    {
        $rules = PackagingRule::with('product')->paginate(25);
        return view('admin.packaging-rules.index', compact('rules'));
    }

    public function create()
    {
        $products = Product::pluck('name', 'id');
        return view('admin.packaging-rules.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'unit_name' => 'required|string',
            'units_per' => 'required|numeric|min:0.0001',
        ]);

        PackagingRule::create($request->only(['product_id', 'unit_name', 'units_per', 'priority', 'is_active']));
        return redirect()->route('admin.packaging-rules.index')->with('success', 'Saved');
    }

    public function edit(PackagingRule $packagingRule)
    {
        $products = Product::pluck('name', 'id');
        return view('admin.packaging-rules.edit', ['rule' => $packagingRule, 'products' => $products]);
    }

    public function update(Request $request, PackagingRule $packagingRule)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'unit_name' => 'required|string',
            'units_per' => 'required|numeric|min:0.0001',
        ]);

        $packagingRule->update($request->only(['product_id', 'unit_name', 'units_per', 'priority', 'is_active']));
        return redirect()->route('admin.packaging-rules.index')->with('success', 'Updated');
    }

    public function destroy(PackagingRule $packagingRule)
    {
        $packagingRule->delete();
        return redirect()->route('admin.packaging-rules.index')->with('success', 'Deleted');
    }
}

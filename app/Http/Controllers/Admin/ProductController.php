<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'unit', 'images'])
            ->latest()
            ->paginate(15);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        $units = Unit::where('is_active', true)->get();

        return view('admin.products.create', compact('categories', 'units'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'unit_id' => 'required|exists:units,id',
            'base_price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:base_price',
            'stock_quantity' => 'required|integer|min:0',
            'min_order_quantity' => 'required|integer|min:1',
            'short_description' => 'nullable|string|max:500',
            'full_description' => 'nullable|string',
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|string|max:100',
            'video_url' => 'nullable|url',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'is_deal' => 'nullable|boolean',
            'deal_end_at' => 'nullable|date',

        ]);

        // Generate slug
        $validated['slug'] = Str::slug($request->name);

        // Ensure slug is unique
        $count = Product::where('slug', $validated['slug'])->count();
        if ($count > 0) {
            $validated['slug'] = $validated['slug'] . '-' . ($count + 1);
        }

        // Handle attributes if provided
        if ($request->has('attributes')) {
            $attributes = [];
            foreach ($request->attributes as $key => $value) {
                if (!empty($key) && !empty($value)) {
                    $attributes[$key] = $value;
                }
            }
            $validated['attributes'] = !empty($attributes) ? json_encode($attributes) : null;
        }
        // 🔥 If deal selected, remove previous deal
        if ($request->boolean('is_deal')) {
            Product::where('is_deal', 1)->update([
                'is_deal' => 0,
                'deal_end_at' => null,
            ]);
        }


        // Set default values
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['is_deal'] = $request->boolean('is_deal');


        $product = Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        $product->load(['category', 'unit', 'images']);
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        $units = Unit::where('is_active', true)->get();

        return view('admin.products.edit', compact('product', 'categories', 'units'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'unit_id' => 'required|exists:units,id',
            'base_price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:base_price',
            'stock_quantity' => 'required|integer|min:0',
            'min_order_quantity' => 'required|integer|min:1',
            'short_description' => 'nullable|string|max:500',
            'full_description' => 'nullable|string',
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|string|max:100',
            'video_url' => 'nullable|url',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'is_deal' => 'nullable|boolean',
            'deal_end_at' => 'nullable|date',

        ]);

        // Update slug if name changed
        if ($product->name !== $request->name) {
            $validated['slug'] = Str::slug($request->name);

            // Ensure slug is unique
            $count = Product::where('slug', $validated['slug'])
                ->where('id', '!=', $product->id)
                ->count();

            if ($count > 0) {
                $validated['slug'] = $validated['slug'] . '-' . time();
            }
        }

        // Handle attributes
        if ($request->has('attributes')) {
            $attributes = [];
            foreach ($request->attributes as $key => $value) {
                if (!empty($key) && !empty($value)) {
                    $attributes[$key] = $value;
                }
            }
            $validated['attributes'] = !empty($attributes) ? json_encode($attributes) : null;
        } else {
            $validated['attributes'] = null;
        }
        // 🔥 Only one deal allowed
        if ($request->boolean('is_deal')) {
            Product::where('is_deal', 1)
                ->where('id', '!=', $product->id)
                ->update([
                    'is_deal' => 0,
                    'deal_end_at' => null,
                ]);
        }


        // Set boolean values
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_deal'] = $request->boolean('is_deal');


        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        // Check if product has any orders before deleting
        // You might want to add this check based on your order system

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete,featured,unfeatured',
            'ids' => 'required|array',
            'ids.*' => 'exists:products,id'
        ]);

        $action = $request->action;
        $ids = $request->ids;

        switch ($action) {
            case 'activate':
                Product::whereIn('id', $ids)->update(['is_active' => true]);
                $message = 'Products activated successfully.';
                break;

            case 'deactivate':
                Product::whereIn('id', $ids)->update(['is_active' => false]);
                $message = 'Products deactivated successfully.';
                break;

            case 'featured':
                Product::whereIn('id', $ids)->update(['is_featured' => true]);
                $message = 'Products marked as featured.';
                break;

            case 'unfeatured':
                Product::whereIn('id', $ids)->update(['is_featured' => false]);
                $message = 'Products removed from featured.';
                break;

            case 'delete':
                Product::whereIn('id', $ids)->delete();
                $message = 'Products deleted successfully.';
                break;
        }

        return redirect()->route('admin.products.index')
            ->with('success', $message);
    }

    public function updateStatus(Request $request, Product $product)
    {
        $request->validate([
            'status' => 'required|boolean'
        ]);

        $product->update(['is_active' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully.'
        ]);
    }
}

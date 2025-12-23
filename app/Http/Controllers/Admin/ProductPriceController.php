<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductPriceController extends Controller
{
    // Show all prices for a product
    public function index(Product $product)
    {
        $prices = $product->prices()->ordered()->get();

        return view('admin.product-prices.index', compact('product', 'prices'));
    }

    // Show create form
    public function create(Product $product)
    {
        return view('admin.product-prices.create', compact('product'));
    }

    // Store new price tier
    public function store(Request $request, Product $product)
    {
        $validated = $request->validate(ProductPrice::validationRules());

        // Additional validation for overlapping ranges
        $this->validatePriceRange($request, $product);

        $price = $product->prices()->create($validated);

        return redirect()->route('admin.products.prices.index', $product->id)
            ->with('success', 'Price tier added successfully.');
    }

    // Show single price tier
    public function show(Product $product, ProductPrice $price)
    {
        return view('admin.product-prices.show', compact('product', 'price'));
    }

    // Edit price tier
    public function edit(Product $product, ProductPrice $price)
    {
        return view('admin.product-prices.edit', compact('product', 'price'));
    }

    // Update price tier
    public function update(Request $request, Product $product, ProductPrice $price)
    {
        $validated = $request->validate(ProductPrice::validationRules($price->id));

        // Additional validation for overlapping ranges (excluding current)
        $this->validatePriceRange($request, $product, $price);

        $price->update($validated);

        return redirect()->route('admin.products.prices.index', $product->id)
            ->with('success', 'Price tier updated successfully.');
    }

    // Delete price tier
    public function destroy(Product $product, ProductPrice $price)
    {
        $price->delete();

        return redirect()->route('admin.products.prices.index', $product->id)
            ->with('success', 'Price tier deleted successfully.');
    }

    // Bulk actions
    public function bulkAction(Request $request, Product $product)
    {
        $request->validate([
            'action' => 'required|in:delete',
            'ids' => 'required|array',
            'ids.*' => 'exists:product_prices,id'
        ]);

        $ids = $request->ids;

        switch ($request->action) {
            case 'delete':
                ProductPrice::whereIn('id', $ids)->delete();
                $message = count($ids) . ' price tier(s) deleted successfully.';
                break;
        }

        return redirect()->route('admin.products.prices.index', $product->id)
            ->with('success', $message);
    }

    // Validate price range doesn't overlap with existing ranges
    private function validatePriceRange(Request $request, Product $product, ProductPrice $exclude = null)
    {
        $minQty = $request->min_quantity;
        $maxQty = $request->max_quantity;

        $query = $product->prices();

        if ($exclude) {
            $query->where('id', '!=', $exclude->id);
        }

        // Check for overlapping ranges
        $overlapping = $query->where(function ($q) use ($minQty, $maxQty) {
            // Case 1: New range starts within existing range
            $q->where(function ($q1) use ($minQty) {
                $q1->where('min_quantity', '<=', $minQty)
                    ->where(function ($q2) use ($minQty) {
                        $q2->where('max_quantity', '>=', $minQty)
                            ->orWhereNull('max_quantity');
                    });
            })
                // Case 2: New range ends within existing range
                ->orWhere(function ($q1) use ($maxQty) {
                    if ($maxQty) {
                        $q1->where('min_quantity', '<=', $maxQty)
                            ->where(function ($q2) use ($maxQty) {
                                $q2->where('max_quantity', '>=', $maxQty)
                                    ->orWhereNull('max_quantity');
                            });
                    }
                })
                // Case 3: New range contains existing range
                ->orWhere(function ($q1) use ($minQty, $maxQty) {
                    $q1->where('min_quantity', '>=', $minQty);

                    if ($maxQty) {
                        $q1->where('max_quantity', '<=', $maxQty);
                    }
                });
        })->exists();

        if ($overlapping) {
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'min_quantity' => 'This quantity range overlaps with an existing price tier.',
                    'max_quantity' => 'This quantity range overlaps with an existing price tier.',
                ]);
        }

        // Check if min_quantity is greater than product's min_order_quantity
        if ($minQty < $product->min_order_quantity) {
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'min_quantity' => "Minimum quantity must be at least {$product->min_order_quantity} (product's minimum order quantity).",
                ]);
        }

        return true;
    }
}

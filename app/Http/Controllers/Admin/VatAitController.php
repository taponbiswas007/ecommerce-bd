<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VatAitSetting;
use App\Models\ProductTaxOverride;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class VatAitController extends Controller
{
    /**
     * Display VAT/AIT settings
     */
    public function index()
    {
        $settings = VatAitSetting::current();
        $categories = Category::where('is_active', true)->get();

        return view('admin.vat-ait.settings', compact('settings', 'categories'));
    }

    /**
     * Update global VAT/AIT settings
     */
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'default_vat_percentage' => 'required|numeric|min:0|max:100',
            'vat_included_in_price' => 'required|in:0,1',
            'default_ait_percentage' => 'required|numeric|min:0|max:100',
            'ait_included_in_price' => 'required|in:0,1',
            'ait_exempt_categories' => 'nullable|string',
            'notes' => 'nullable|string|max:1000',
            'effective_from' => 'nullable|date_format:Y-m-d\TH:i',
        ]);

        // Explicitly set checkbox values (HTML doesn't send unchecked values)
        $validated['vat_enabled'] = $request->has('vat_enabled');
        $validated['ait_enabled'] = $request->has('ait_enabled');

        // Convert select field values to boolean
        $validated['vat_included_in_price'] = (bool) (int) $request->input('vat_included_in_price', 0);
        $validated['ait_included_in_price'] = (bool) (int) $request->input('ait_included_in_price', 0);

        // Handle datetime-local format conversion
        if ($request->has('effective_from') && $request->effective_from) {
            try {
                $effectiveFrom = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $request->effective_from);
                $validated['effective_from'] = $effectiveFrom->format('Y-m-d H:i:s');

                if ($effectiveFrom->isFuture()) {
                    // Create new settings record for future effective date
                    $settings = VatAitSetting::create($validated);
                    return back()->with('success', 'New VAT/AIT setting scheduled for ' . $effectiveFrom->format('M d, Y H:i'));
                }
            } catch (\Exception $e) {
                return back()->withErrors(['effective_from' => 'Invalid date format']);
            }
        } else {
            $validated['effective_from'] = null;
        }

        // Update current settings
        $settings = VatAitSetting::current();
        $settings->update($validated);

        return back()->with('success', 'VAT/AIT settings updated successfully!');
    }

    /**
     * Display product-wise tax configuration
     */
    public function productTaxes()
    {
        $products = Product::with('taxOverride')
            ->where('is_active', true)
            ->orderBy('name')
            ->paginate(20);

        return view('admin.vat-ait.product-taxes', compact('products'));
    }

    /**
     * Show form to set product tax override
     */
    public function editProductTax(Product $product)
    {
        $override = $product->taxOverride;
        $globalSettings = VatAitSetting::current();

        return view('admin.vat-ait.edit-product-tax', compact('product', 'override', 'globalSettings'));
    }

    /**
     * Update product tax override
     */
    public function updateProductTax(Request $request, Product $product)
    {
        $validated = $request->validate([
            'vat_percentage' => 'nullable|numeric|min:0|max:100',
            'vat_included_in_price' => 'nullable',
            'ait_percentage' => 'nullable|numeric|min:0|max:100',
            'ait_included_in_price' => 'nullable',
            'reason' => 'nullable|string|max:500',
            'effective_from' => 'nullable|date_format:Y-m-d H:i:s',
            'effective_until' => 'nullable|date_format:Y-m-d H:i:s',
        ]);

        // Explicitly set checkbox values (HTML doesn't send unchecked values)
        $validated['override_vat'] = $request->has('override_vat');
        $validated['override_ait'] = $request->has('override_ait');
        $validated['vat_exempt'] = $request->has('vat_exempt');
        $validated['ait_exempt'] = $request->has('ait_exempt');

        // Convert empty vat_included_in_price to null
        if ($validated['vat_included_in_price'] === '') {
            $validated['vat_included_in_price'] = null;
        } else {
            $validated['vat_included_in_price'] = (bool) $validated['vat_included_in_price'];
        }

        // Convert empty ait_included_in_price to null
        if ($validated['ait_included_in_price'] === '') {
            $validated['ait_included_in_price'] = null;
        } else {
            $validated['ait_included_in_price'] = (bool) $validated['ait_included_in_price'];
        }

        // Check if any tax override is enabled
        if (!$validated['override_vat'] && !$validated['override_ait'] && !$validated['vat_exempt'] && !$validated['ait_exempt']) {
            // Delete override if exists
            $product->taxOverride?->delete();
            return back()->with('success', 'Tax override removed. Product will use global settings.');
        }

        // Create or update override
        $override = $product->taxOverride ?? new ProductTaxOverride();
        $override->product_id = $product->id;
        $override->fill($validated);
        $override->save();

        return back()->with('success', 'Product tax configuration updated successfully!');
    }

    /**
     * Remove product tax override
     */
    public function removeProductTax(Product $product)
    {
        $product->taxOverride?->delete();

        return back()->with('success', 'Tax override removed. Product will use global settings.');
    }

    /**
     * Bulk update tax settings for multiple products
     */
    public function bulkUpdateProductTax(Request $request)
    {
        $validated = $request->validate([
            'product_ids' => 'required|array|min:1',
            'product_ids.*' => 'exists:products,id',
            'override_vat' => 'boolean',
            'vat_percentage' => 'nullable|numeric|min:0|max:100',
            'override_ait' => 'boolean',
            'ait_percentage' => 'nullable|numeric|min:0|max:100',
            'vat_exempt' => 'boolean',
            'ait_exempt' => 'boolean',
            'reason' => 'nullable|string|max:500',
        ]);

        foreach ($validated['product_ids'] as $productId) {
            $product = Product::find($productId);
            if (!$product) continue;

            $override = $product->taxOverride ?? new ProductTaxOverride();
            $override->product_id = $productId;

            if ($request->has('override_vat')) {
                $override->override_vat = $validated['override_vat'];
                $override->vat_percentage = $validated['vat_percentage'] ?? null;
            }

            if ($request->has('override_ait')) {
                $override->override_ait = $validated['override_ait'];
                $override->ait_percentage = $validated['ait_percentage'] ?? null;
            }

            if ($request->has('vat_exempt')) {
                $override->vat_exempt = $validated['vat_exempt'];
            }

            if ($request->has('ait_exempt')) {
                $override->ait_exempt = $validated['ait_exempt'];
            }

            if ($request->has('reason')) {
                $override->reason = $validated['reason'];
            }

            $override->save();
        }

        return back()->with('success', 'Tax settings updated for ' . count($validated['product_ids']) . ' products!');
    }

    /**
     * Display tax history/audit log
     */
    public function history()
    {
        $settings = VatAitSetting::withTrashed()
            ->orderBy('effective_from', 'desc')
            ->paginate(15);

        return view('admin.vat-ait.history', compact('settings'));
    }

    /**
     * Display tax report
     */
    public function report()
    {
        $totalProducts = Product::count();
        $productsWithOverride = ProductTaxOverride::whereNull('deleted_at')->count();
        $vatExemptProducts = ProductTaxOverride::where('vat_exempt', true)->whereNull('deleted_at')->count();
        $aitExemptProducts = ProductTaxOverride::where('ait_exempt', true)->whereNull('deleted_at')->count();

        $currentSettings = VatAitSetting::current();

        // Get products with custom tax rates
        $customTaxProducts = Product::with('taxOverride')
            ->whereHas('taxOverride', function ($query) {
                $query->where('override_vat', true)
                    ->orWhere('override_ait', true)
                    ->whereNull('deleted_at');
            })
            ->get();

        $stats = [
            'total_products' => $totalProducts,
            'products_with_override' => $productsWithOverride,
            'vat_exempt_products' => $vatExemptProducts,
            'ait_exempt_products' => $aitExemptProducts,
            'custom_tax_products' => $customTaxProducts->count(),
        ];

        return view('admin.vat-ait.report', compact('stats', 'currentSettings', 'customTaxProducts'));
    }

    /**
     * Search and filter products by tax configuration
     */
    public function searchProductTax(Request $request)
    {
        $query = Product::with('taxOverride', 'category');

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('id', $search);
        }

        if ($request->tax_filter === 'has_override') {
            $query->whereHas('taxOverride', function ($q) {
                $q->whereNull('deleted_at');
            });
        } elseif ($request->tax_filter === 'vat_override') {
            $query->whereHas('taxOverride', function ($q) {
                $q->where('override_vat', true)->whereNull('deleted_at');
            });
        } elseif ($request->tax_filter === 'ait_override') {
            $query->whereHas('taxOverride', function ($q) {
                $q->where('override_ait', true)->whereNull('deleted_at');
            });
        } elseif ($request->tax_filter === 'vat_exempt') {
            $query->whereHas('taxOverride', function ($q) {
                $q->where('vat_exempt', true)->whereNull('deleted_at');
            });
        } elseif ($request->tax_filter === 'ait_exempt') {
            $query->whereHas('taxOverride', function ($q) {
                $q->where('ait_exempt', true)->whereNull('deleted_at');
            });
        }

        $products = $query->paginate(20);

        return view('admin.vat-ait.product-taxes', compact('products'));
    }

    /**
     * Export product tax configuration as CSV
     */
    public function exportProductTax()
    {
        $products = Product::with('taxOverride', 'category')->get();

        $csv = "Product ID,Product Name,Category,VAT %,VAT Included,AIT %,AIT Included,VAT Exempt,AIT Exempt,Reason\n";

        $settings = VatAitSetting::current();

        foreach ($products as $product) {
            $override = $product->taxOverride;

            $vatPercentage = $override && $override->override_vat ? $override->vat_percentage : $settings->default_vat_percentage;
            $vatIncluded = $override && $override->vat_included_in_price !== null ? ($override->vat_included_in_price ? 'Yes' : 'No') : ($settings->vat_included_in_price ? 'Yes' : 'No');
            $aitPercentage = $override && $override->override_ait ? $override->ait_percentage : $settings->default_ait_percentage;
            $aitIncluded = $override && $override->ait_included_in_price !== null ? ($override->ait_included_in_price ? 'Yes' : 'No') : ($settings->ait_included_in_price ? 'Yes' : 'No');
            $vatExempt = $override && $override->vat_exempt ? 'Yes' : 'No';
            $aitExempt = $override && $override->ait_exempt ? 'Yes' : 'No';
            $reason = $override && $override->reason ? '"' . str_replace('"', '""', $override->reason) . '"' : '';

            $csv .= "$product->id,\"$product->name\"," . ($product->category ? $product->category->name : '') . ",$vatPercentage,$vatIncluded,$aitPercentage,$aitIncluded,$vatExempt,$aitExempt,$reason\n";
        }

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="product-taxes-' . date('Y-m-d-H-i-s') . '.csv"',
        ]);
    }
}

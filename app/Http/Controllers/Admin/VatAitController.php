<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VatAitSetting;
use App\Models\ProductTaxOverride;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Log;
use Throwable;

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
        $current = VatAitSetting::current();

        $validated = $request->validate([
            // VAT
            'vat_enabled' => 'required|in:0,1',
            'default_vat_percentage' => 'nullable|numeric|min:0|max:100|required_if:vat_enabled,1',
            'vat_included_in_price' => 'nullable|in:0,1|required_if:vat_enabled,1',
            // AIT
            'ait_enabled' => 'required|in:0,1',
            'default_ait_percentage' => 'nullable|numeric|min:0|max:100|required_if:ait_enabled,1',
            'ait_included_in_price' => 'nullable|in:0,1|required_if:ait_enabled,1',
            // Other
            'ait_exempt_categories' => 'nullable|string',
            'notes' => 'nullable|string|max:1000',
            'effective_from' => 'nullable|date_format:Y-m-d\\TH:i',
        ]);

        // Note: do not convert booleans here; keys may be missing when disabled.
        // We normalize and backfill further below after validation and date handling.

        // Handle datetime-local format conversion
        try {
            if ($request->has('effective_from') && $request->effective_from) {
                try {
                    $tz = config('app.timezone');
                    $effectiveFrom = \Carbon\Carbon::createFromFormat('Y-m-d\\TH:i', $request->effective_from, $tz);
                    $validated['effective_from'] = $effectiveFrom->format('Y-m-d H:i:s');

                    if ($effectiveFrom->isFuture()) {
                        // Create new settings record for future effective date
                        VatAitSetting::create($validated);
                        return back()->with('success', 'New VAT/AIT setting scheduled for ' . $effectiveFrom->format('M d, Y H:i'));
                    }
                } catch (\Exception $e) {
                    return back()->withErrors(['effective_from' => 'Invalid date format']);
                }
            } else {
                // If no date provided, treat as immediate effect
                $validated['effective_from'] = now()->format('Y-m-d H:i:s');
            }

            // Normalize booleans
            $vatEnabled = (bool) ((int) $request->input('vat_enabled', 0));
            $aitEnabled = (bool) ((int) $request->input('ait_enabled', 0));
            $validated['vat_enabled'] = $vatEnabled;
            $validated['ait_enabled'] = $aitEnabled;

            // Backfill missing fields when inputs are disabled in UI
            if (!array_key_exists('default_vat_percentage', $validated) || $validated['default_vat_percentage'] === null) {
                $validated['default_vat_percentage'] = $current->default_vat_percentage;
            }
            if (!array_key_exists('vat_included_in_price', $validated) || $validated['vat_included_in_price'] === null) {
                $validated['vat_included_in_price'] = (int) $current->vat_included_in_price;
            }
            if (!array_key_exists('default_ait_percentage', $validated) || $validated['default_ait_percentage'] === null) {
                $validated['default_ait_percentage'] = $current->default_ait_percentage;
            }
            if (!array_key_exists('ait_included_in_price', $validated) || $validated['ait_included_in_price'] === null) {
                $validated['ait_included_in_price'] = (int) $current->ait_included_in_price;
            }

            // Convert to boolean for included_in_price fields
            $validated['vat_included_in_price'] = (bool) ((int) $validated['vat_included_in_price']);
            $validated['ait_included_in_price'] = (bool) ((int) $validated['ait_included_in_price']);

            // Change detection and scheduling logic
            $fields = [
                'default_vat_percentage',
                'vat_enabled',
                'vat_included_in_price',
                'default_ait_percentage',
                'ait_enabled',
                'ait_included_in_price',
                'ait_exempt_categories',
                'notes',
            ];

            $incoming = [];
            foreach ($fields as $f) {
                if (array_key_exists($f, $validated)) {
                    $incoming[$f] = $validated[$f];
                }
            }

            // If effective_from is future → schedule, else update current
            $eff = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $validated['effective_from']);
            if ($eff->isFuture()) {
                // If there is already a scheduled row at the same time, update it instead of inserting
                $existingScheduled = VatAitSetting::where('effective_from', $validated['effective_from'])->first();
                if ($existingScheduled) {
                    $existingScheduled->update($incoming);
                    return back()->with('success', 'Scheduled VAT/AIT settings updated for ' . $eff->format('M d, Y H:i'));
                }

                // If latest scheduled has same values, avoid duplicate insert
                $latestScheduled = VatAitSetting::where('effective_from', '>', now())
                    ->orderByDesc('effective_from')
                    ->first();
                if ($latestScheduled) {
                    $same = true;
                    foreach ($incoming as $k => $v) {
                        if ($latestScheduled->$k != $v) {
                            $same = false;
                            break;
                        }
                    }
                    if ($same) {
                        return back()->with('success', 'No changes to schedule. Latest scheduled settings already match.');
                    }
                }

                // Create new scheduled settings
                VatAitSetting::create($validated);
                return back()->with('success', 'New VAT/AIT setting scheduled for ' . $eff->format('M d, Y H:i'));
            }

            // Immediate update path
            $currentValues = [];
            foreach ($fields as $f) {
                $currentValues[$f] = $current->$f;
            }
            $noChanges = true;
            foreach ($fields as $f) {
                if ($currentValues[$f] != ($incoming[$f] ?? $currentValues[$f])) {
                    $noChanges = false;
                    break;
                }
            }
            if ($noChanges) {
                return back()->with('success', 'No changes detected.');
            }

            $settings = VatAitSetting::current();
            $settings->update($validated);

            return back()->with('success', 'VAT/AIT settings updated successfully!');
        } catch (Throwable $t) {
            Log::error('Failed to save VAT/AIT settings', [
                'error' => $t->getMessage(),
                'trace' => $t->getTraceAsString(),
                'payload' => $validated,
                'user_id' => FacadesAuth::user()?->id,
            ]);
            return back()->with('error', 'Failed to save VAT/AIT settings. Please try again or contact support.');
        }
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
        // Convert datetime-local format to database format before validation
        if ($request->has('effective_from') && $request->effective_from) {
            try {
                $tz = config('app.timezone');
                $efFrom = \Carbon\Carbon::createFromFormat('Y-m-d\\TH:i', $request->effective_from, $tz);
                $request->merge(['effective_from' => $efFrom->format('Y-m-d H:i:s')]);
            } catch (\Exception $e) {
                return back()->withErrors(['effective_from' => 'Invalid date format for effective_from']);
            }
        }

        if ($request->has('effective_until') && $request->effective_until) {
            try {
                $tz = config('app.timezone');
                $efUntil = \Carbon\Carbon::createFromFormat('Y-m-d\\TH:i', $request->effective_until, $tz);
                $request->merge(['effective_until' => $efUntil->format('Y-m-d H:i:s')]);
            } catch (\Exception $e) {
                return back()->withErrors(['effective_until' => 'Invalid date format for effective_until']);
            }
        }

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

        try {
            // Create or update override
            $override = $product->taxOverride ?? new ProductTaxOverride();
            $override->product_id = $product->id;
            $override->fill($validated);
            $override->save();

            return back()->with('success', 'Product tax configuration updated successfully!');
        } catch (Throwable $t) {
            Log::error('Failed to save product tax override', [
                'product_id' => $product->id,
                'error' => $t->getMessage(),
                'payload' => $validated,
            ]);
            return back()->with('error', 'Failed to save product tax settings. Please try again.')->withInput();
        }
    }

    /**
     * Remove product tax override
     */
    public function removeProductTax(Product $product)
    {
        try {
            $product->taxOverride?->delete();
            return back()->with('success', 'Tax override removed. Product will use global settings.');
        } catch (Throwable $t) {
            Log::error('Failed to remove product tax override', [
                'product_id' => $product->id,
                'error' => $t->getMessage(),
            ]);
            return back()->with('error', 'Failed to remove product tax override.');
        }
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

        try {
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
        } catch (Throwable $t) {
            Log::error('Failed bulk product tax update', [
                'error' => $t->getMessage(),
                'payload' => $validated,
            ]);
            return back()->with('error', 'Bulk update failed. Please try again.');
        }
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

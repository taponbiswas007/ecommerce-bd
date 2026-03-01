<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BulkProductPriceController extends Controller
{
    /**
     * Show bulk price update page
     */
    public function index()
    {
        $categories = Category::where('is_active', true)->get();
        $units = Unit::where('is_active', true)->get();

        return view('admin.products.bulk-price-update', compact('categories', 'units'));
    }

    /**
     * Get products for bulk price update with filtering
     */
    public function getProducts(Request $request)
    {
        $query = Product::with(['category', 'unit', 'prices']);

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by price range
        if ($request->filled('min_price')) {
            $query->where('base_price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('base_price', '<=', $request->max_price);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        // Filter by discount status
        if ($request->filled('discount_filter')) {
            if ($request->discount_filter === 'with') {
                $query->whereNotNull('discount_price');
            } elseif ($request->discount_filter === 'without') {
                $query->whereNull('discount_price');
            }
        }

        // Search
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
                // ->orWhere('slug', 'like', "%{$search}%")
                // ->orWhere('sku', 'like', "%{$search}%")
                // ->orWhere('id', '=', is_numeric($search) ? $search : -1);
            });
        }

        $products = $query->paginate(50);

        return response()->json([
            'products' => $products,
            'total' => $products->total(),
        ]);
    }

    /**
     * Update prices in bulk
     */
    public function updatePrices(Request $request)
    {
        $request->validate([
            'update_type' => 'required|in:fixed,percentage,formula',
            'price_field' => 'required|in:base_price,discount_price',
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:products,id',
            'fixed_price' => 'nullable|numeric|min:0',
            'percentage' => 'nullable|numeric',
            'percentage_direction' => 'nullable|in:increase,decrease',
            'formula_type' => 'nullable|in:increase,decrease',
            'formula_value' => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $updateType = $request->update_type;
            $priceField = $request->price_field;
            $ids = $request->ids;
            $count = 0;

            foreach ($ids as $id) {
                $product = Product::find($id);
                if (!$product) continue;

                $currentPrice = $priceField === 'base_price'
                    ? $product->base_price
                    : ($product->discount_price ?? $product->base_price);

                if ($updateType === 'fixed') {
                    $newPrice = $request->fixed_price;
                } elseif ($updateType === 'percentage') {
                    $percentage = $request->percentage;
                    $direction = $request->percentage_direction ?? 'increase';

                    if ($direction === 'increase') {
                        $newPrice = $currentPrice * (1 + ($percentage / 100));
                    } else {
                        $newPrice = $currentPrice * (1 - ($percentage / 100));
                    }
                } elseif ($updateType === 'formula') {
                    $value = $request->formula_value;
                    if ($request->formula_type === 'increase') {
                        $newPrice = $currentPrice + $value;
                    } else {
                        $newPrice = max(0, $currentPrice - $value);
                    }
                }

                // Update the appropriate price field
                $product->{$priceField} = max(0, $newPrice);

                // If updating discount_price, ensure it's less than base_price
                if ($priceField === 'discount_price' && $product->discount_price >= $product->base_price) {
                    $product->discount_price = null;
                }

                $product->save();
                $count++;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Successfully updated {$priceField} for {$count} products!",
                'count' => $count,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk price update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating prices: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Batch update stock visibility (hide from frontend without deleting)
     */
    public function toggleStockVisibility(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:products,id',
            'visible' => 'required|boolean',
        ]);

        try {
            Product::whereIn('id', $request->ids)->update([
                'hide_from_frontend' => !$request->visible,
            ]);

            $action = $request->visible ? 'shown on' : 'hidden from';

            return response()->json([
                'success' => true,
                'message' => "Successfully {$action} frontend for " . count($request->ids) . " products!",
            ]);
        } catch (\Exception $e) {
            Log::error('Stock visibility error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating visibility',
            ], 500);
        }
    }

    /**
     * Apply discount to multiple products
     */
    public function applyDiscount(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:products,id',
            'discount_type' => 'required|in:percentage,fixed,absolute',
            'discount_value' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $count = 0;
            foreach ($request->ids as $id) {
                $product = Product::find($id);
                if (!$product) continue;

                $discountType = $request->discount_type;
                $discountValue = $request->discount_value;

                if ($discountType === 'percentage') {
                    // Percentage off base price
                    $product->discount_price = $product->base_price * (1 - ($discountValue / 100));
                } elseif ($discountType === 'fixed') {
                    // Fixed amount discount
                    $product->discount_price = max(0, $product->base_price - $discountValue);
                } elseif ($discountType === 'absolute') {
                    // Absolute discount price
                    $product->discount_price = $discountValue;
                }

                // Ensure discount price is less than base price
                if ($product->discount_price >= $product->base_price) {
                    $product->discount_price = null;
                } else {
                    $product->save();
                    $count++;
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Successfully applied discounts to {$count} products!",
                'count' => $count,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk discount error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error applying discounts: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove discount from multiple products
     */
    public function removeDiscount(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:products,id',
        ]);

        try {
            $count = Product::whereIn('id', $request->ids)->update(['discount_price' => null]);

            return response()->json([
                'success' => true,
                'message' => "Successfully removed discounts from {$count} products!",
            ]);
        } catch (\Exception $e) {
            Log::error('Remove discount error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error removing discounts',
            ], 500);
        }
    }

    /**
     * Apply tier pricing to multiple products
     */
    public function applyTiers(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:products,id',
            'tiers' => 'required|array|min:1',
            'tiers.*.min_quantity' => 'required|integer|min:1',
            'tiers.*.max_quantity' => 'nullable|integer',
            'tiers.*.price' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $count = 0;
            foreach ($request->ids as $productId) {
                $product = Product::find($productId);
                if (!$product) continue;

                // Delete existing tiers
                $product->prices()->delete();

                // Add new tiers
                foreach ($request->tiers as $tier) {
                    $product->prices()->create([
                        'min_quantity' => $tier['min_quantity'],
                        'max_quantity' => $tier['max_quantity'] ?? null,
                        'price' => $tier['price'],
                    ]);
                }

                $count++;
            }

            DB::commit();

            $tierCount = count($request->tiers);
            return response()->json([
                'success' => true,
                'message' => "Successfully applied {$tierCount} tier(s) to {$count} products!",
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk tier pricing error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error applying tiers: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get AI price suggestions
     */
    public function aiSuggest(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:products,id',
        ]);

        try {
            $products = Product::with('category')->whereIn('id', $request->ids)->get();

            $productData = $products->map(function ($product) {
                return [
                    'name' => $product->name,
                    'category' => $product->category->name ?? 'N/A',
                    'current_price' => $product->base_price,
                    'discount_price' => $product->discount_price,
                    'stock' => $product->stock_quantity,
                ];
            });

            $aiService = new \App\Services\AI\AIService();

            $prompt = "Analyze these products and suggest optimal pricing strategies:\n\n" .
                json_encode($productData, JSON_PRETTY_PRINT) .
                "\n\nProvide pricing recommendations considering market trends, competitiveness, and profit margins.";

            $result = $aiService->generate($prompt);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'suggestion' => $result['content'],
                ]);
            } else {
                throw new \Exception($result['error'] ?? 'AI service failed');
            }
        } catch (\Exception $e) {
            Log::error('AI suggestion error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error getting AI suggestions: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * AI price optimization
     */
    public function aiOptimize(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:products,id',
        ]);

        try {
            $products = Product::with('category')->whereIn('id', $request->ids)->get();

            $productData = $products->map(function ($product) {
                return [
                    'name' => $product->name,
                    'category' => $product->category->name ?? 'N/A',
                    'current_price' => $product->base_price,
                    'sold_count' => $product->sold_count,
                    'view_count' => $product->view_count,
                    'stock' => $product->stock_quantity,
                ];
            });

            $aiService = new \App\Services\AI\AIService();

            $prompt = "Optimize pricing for these products based on their performance data:\n\n" .
                json_encode($productData, JSON_PRETTY_PRINT) .
                "\n\nConsider conversion rates, stock levels, and sales velocity. Suggest specific price adjustments.";

            $result = $aiService->generate($prompt);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'optimization' => $result['content'],
                ]);
            } else {
                throw new \Exception($result['error'] ?? 'AI service failed');
            }
        } catch (\Exception $e) {
            Log::error('AI optimization error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error optimizing prices: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * AI market analysis
     */
    public function aiMarket(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:products,id',
        ]);

        try {
            $products = Product::with('category')->whereIn('id', $request->ids)->get();

            $productData = $products->map(function ($product) {
                return [
                    'name' => $product->name,
                    'category' => $product->category->name ?? 'N/A',
                    'current_price' => $product->base_price,
                ];
            });

            $aiService = new \App\Services\AI\AIService();

            $prompt = "Perform competitive market analysis for these products:\n\n" .
                json_encode($productData, JSON_PRETTY_PRINT) .
                "\n\nAnalyze market positioning, competitive pricing, and suggest strategies to stay competitive in Bangladesh market.";

            $result = $aiService->generate($prompt);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'analysis' => $result['content'],
                ]);
            } else {
                throw new \Exception($result['error'] ?? 'AI service failed');
            }
        } catch (\Exception $e) {
            Log::error('AI market analysis error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error analyzing market: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Export prices to CSV
     */
    public function exportPrices(Request $request)
    {
        try {
            $query = Product::with(['category', 'prices']);

            // Apply same filters as getProducts
            if ($request->filled('category_id')) {
                $query->where('category_id', $request->category_id);
            }
            if ($request->filled('min_price')) {
                $query->where('base_price', '>=', $request->min_price);
            }
            if ($request->filled('max_price')) {
                $query->where('base_price', '<=', $request->max_price);
            }
            if ($request->filled('status')) {
                $query->where('is_active', $request->status);
            }
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                });
            }

            $products = $query->get();

            $csvData = [];
            $csvData[] = ['ID', 'Name', 'SKU', 'Category', 'Base Price', 'Discount Price', 'Tier Prices'];

            foreach ($products as $product) {
                $tierPrices = $product->prices->map(function ($price) {
                    return "{$price->min_quantity}-{$price->max_quantity}: " . $price->price;
                })->implode('; ');

                $csvData[] = [
                    $product->id,
                    $product->name,
                    $product->sku ?? 'N/A',
                    $product->category->name ?? 'N/A',
                    $product->base_price,
                    $product->discount_price ?? 'N/A',
                    $tierPrices ?: 'None',
                ];
            }

            $fileName = 'product_prices_' . date('Y-m-d_His') . '.csv';
            $filePath = storage_path('app/exports/' . $fileName);

            // Ensure directory exists
            if (!file_exists(storage_path('app/exports'))) {
                mkdir(storage_path('app/exports'), 0755, true);
            }

            $file = fopen($filePath, 'w');
            foreach ($csvData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);

            return response()->download($filePath, $fileName)->deleteFileAfterSend();
        } catch (\Exception $e) {
            Log::error('Export error: ' . $e->getMessage());
            return back()->with('error', 'Failed to export prices');
        }
    }
}

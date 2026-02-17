<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DropshippingProduct;
use App\Models\DropshippingSetting;
use App\Services\CJDropshippingService;
use Illuminate\Http\Request;
use Exception;

class DropshippingProductController extends Controller
{
    protected $cjService;

    public function __construct(CJDropshippingService $cjService)
    {
        $this->cjService = $cjService;
    }

    /**
     * Display a listing of dropshipping products.
     */
    public function index(Request $request)
    {
        $query = DropshippingProduct::query();

        // Filter by search
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('cj_product_id', 'like', "%{$search}%")
                ->orWhere('sku', 'like', "%{$search}%");
        }

        // Filter by status
        if ($request->has('status') && !empty($request->status)) {
            $query->where('is_active', $request->status === 'active' ? true : false);
        }

        // Filter by availability
        if ($request->has('availability') && !empty($request->availability)) {
            if ($request->availability === 'available') {
                $query->where('is_available', true);
            } elseif ($request->availability === 'out_of_stock') {
                $query->where('stock', 0);
            }
        }

        $products = $query->paginate(20);

        return view('admin.dropshipping.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new dropshipping product.
     */
    public function create()
    {
        return view('admin.dropshipping.products.create');
    }

    /**
     * Search and import product from CJ
     */
    public function search(Request $request)
    {
        $request->validate([
            'keyword' => 'nullable|string|min:2',
        ]);

        try {
            if (!$this->cjService->isConfigured()) {
                return response()->json([
                    'success' => false,
                    'message' => 'CJ API is not configured. Please set up API credentials in settings.'
                ], 400);
            }

            $keyword = trim((string) $request->keyword);

            if ($keyword === '') {
                $results = $this->cjService->getSuggestedProducts(1, 20);

                return response()->json([
                    'success' => true,
                    'data' => $results,
                    'suggested' => true,
                    'message' => 'Showing suggested products'
                ]);
            }

            $results = $this->cjService->searchProducts($keyword, 1, 20);

            if (empty($results)) {
                $results = $this->cjService->getSuggestedProducts(1, 20);

                return response()->json([
                    'success' => true,
                    'data' => $results,
                    'suggested' => true,
                    'message' => 'No results found. Showing suggested products.'
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => $results,
                'suggested' => false
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Import product from CJ
     */
    public function import(Request $request)
    {
        $request->validate([
            'cj_product_id' => 'required|string',
            'selling_price' => 'required|numeric|min:0',
            'image_url' => 'nullable|url',
        ]);

        try {
            $product = $this->cjService->syncProduct(
                $request->cj_product_id,
                $request->selling_price,
                $request->image_url
            );

            return response()->json([
                'success' => true,
                'message' => 'Product imported successfully',
                'data' => $product
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $product = DropshippingProduct::findOrFail($id);
        return view('admin.dropshipping.products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $product = DropshippingProduct::findOrFail($id);

        $validated = $request->validate([
            'selling_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'is_active' => 'required|boolean',
            'is_available' => 'required|boolean',
        ]);

        $product->update($validated);

        // Recalculate profit margin
        $product->profit_margin = $product->selling_price - $product->unit_price;
        $product->save();

        return redirect()->route('admin.dropshipping.products.index')
            ->with('success', 'Product updated successfully');
    }

    /**
     * Show details of a dropshipping product.
     */
    public function show($id)
    {
        $product = DropshippingProduct::findOrFail($id);
        $orders = $product->orderItems()->with(['dropshippingOrder.order.user'])->paginate(10);

        return view('admin.dropshipping.products.show', compact('product', 'orders'));
    }

    /**
     * Bulk update product prices
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'integer|exists:dropshipping_products,id',
            'action' => 'required|in:price,status,availability',
            'margin_percent' => 'nullable|numeric|min:0|max:100',
            'status' => 'nullable|in:active,inactive',
        ]);

        try {
            $products = DropshippingProduct::whereIn('id', $request->product_ids)->get();

            if ($request->action === 'price' && $request->margin_percent !== null) {
                foreach ($products as $product) {
                    $product->selling_price = $product->unit_price * (1 + ($request->margin_percent / 100));
                    $product->profit_margin = $product->selling_price - $product->unit_price;
                    $product->save();
                }
            } elseif ($request->action === 'status' && $request->status) {
                $isActive = $request->status === 'active';
                DropshippingProduct::whereIn('id', $request->product_ids)->update(['is_active' => $isActive]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Products updated successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Delete a product
     */
    public function destroy($id)
    {
        $product = DropshippingProduct::findOrFail($id);
        $product->delete();

        return redirect()->route('admin.dropshipping.products.index')
            ->with('success', 'Product deleted successfully');
    }
}

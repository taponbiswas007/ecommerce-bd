<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;

class FrontendController extends Controller
{
    /**
     * Display mega homepage
     */
    public function index()
    {
        // Build hero slider products: top-selling 2 per active category (fallback via ordering)
        $heroProducts = Category::where('is_active', true)
            ->with(['products' => function ($query) {
                $query->select('products.*')
                    ->with(['primaryImage', 'images', 'unit', 'category'])
                    ->where('is_active', true)
                    ->whereNotNull('slug')
                    ->where('slug', '!=', '')
                    ->where('stock_quantity', '>', 0)
                    ->orderByDesc('sold_count')
                    ->orderByDesc('created_at')
                    ->take(2);
            }])->get()
            ->flatMap->products
            ->unique('id')
            ->values();

        // Get featured products
        $featuredProducts = Product::select('products.*')
            ->with(['primaryImage', 'images', 'category', 'unit'])
            ->where('is_featured', true)
            ->where('is_active', true)
            ->whereNotNull('slug')
            ->where('slug', '!=', '')
            ->where('stock_quantity', '>', 0)
            ->orderBy('created_at', 'desc')
            ->limit(12)
            ->get();

        // Get categories with counts
        $categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->withCount(['products' => function ($query) {
                $query->where('is_active', true)
                    ->where('stock_quantity', '>', 0);
            }])
            ->orderBy('order')
            ->limit(12)
            ->get();

        // Get top brands
        $brands = Brand::where('is_active', true)
            ->orderBy('name')
            ->limit(8)
            ->get();

        // Get flash sale products - products with discount_price
        $flashSaleProducts = Product::select('products.*')
            ->with(['primaryImage', 'images', 'unit'])
            ->where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->whereNotNull('discount_price')
            ->whereColumn('discount_price', '<', 'base_price') // Using whereColumn for comparison
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // Get new arrivals
        $newArrivals = Product::select('products.*')
            ->with(['primaryImage', 'images', 'unit'])
            ->where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        $dealProduct = Product::select('products.*')
            ->with(['primaryImage', 'unit'])
            ->where('is_deal', 1)
            ->where('is_active', 1)
            ->whereNotNull('slug')
            ->where('slug', '!=', '')
            ->where('stock_quantity', '>', 0)
            ->where('deal_end_at', '>=', now())
            ->latest('deal_end_at')
            ->first();


        return view('home', compact(
            'heroProducts',
            'featuredProducts',
            'categories',
            'brands',
            'flashSaleProducts',
            'newArrivals',
            'dealProduct'
        ));
    }

    /**
     * Display shop page
     */
    public function shop(Request $request)
    {
        $query = Product::select('products.*') // 🔴 VERY IMPORTANT
            ->with(['primaryImage', 'category'])
            ->addSelect([
                'average_rating',
                'total_reviews'
            ])
            ->where('is_active', true)
            ->where('stock_quantity', '>', 0);

        // Search filter
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('short_description', 'like', '%' . $request->search . '%')
                    ->orWhere('full_description', 'like', '%' . $request->search . '%');
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Price filter
        if ($request->filled('min_price')) {
            $query->where('base_price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('base_price', '<=', $request->max_price);
        }

        // Sort
        switch ($request->get('sort_by', 'newest')) {
            case 'price_low':
                $query->orderBy('base_price', 'asc');
                break;

            case 'price_high':
                $query->orderBy('base_price', 'desc');
                break;

            case 'name':
                $query->orderBy('name', 'asc');
                break;

            case 'popular':
                $query->orderBy('view_count', 'desc');
                break;

            case 'discount':
                $query->whereNotNull('discount_price')
                    ->whereColumn('discount_price', '<', 'base_price')
                    ->orderByRaw('(base_price - discount_price) DESC');
                break;

            default:
                $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(12)->withQueryString();

        $categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->with('children')
            ->get();

        return view('shop', compact('products', 'categories'));
    }


    /**
     * Display product details
     */
    public function productShow($slug)
    {
        $product = Product::with(['images', 'category', 'unit'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Increment view count
        $product->increment('view_count');

        // Related products
        $relatedProducts = Product::with('images')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->limit(4)
            ->get();

        return view('pages.productdetails', compact('product', 'relatedProducts'));
    }

    /**
     * Display category products
     */
    public function categoryShow($slug)
    {
        $category = Category::with('children')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Parent + child category IDs
        $categoryIds = $category->children->pluck('id')->push($category->id);

        $products = Product::select('products.*') // 🔴 MUST
            ->with(['primaryImage', 'images', 'unit', 'prices'])
            ->addSelect([
                'average_rating',
                'total_reviews'
            ])
            ->whereIn('category_id', $categoryIds)
            ->where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->orderBy('created_at', 'desc')
            ->paginate(12)
            ->withQueryString();

        return view('category.show', compact('category', 'products'));
    }


    /**
     * Quick view product
     */
    public function quickView($hashid)
    {
        $product = Product::with(['images', 'category', 'unit', 'prices'])
            ->where('id', Product::findByHashid($hashid)?->id)
            ->where('is_active', true)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'product' => $product,
            'html' => view('partials.quick-view', compact('product'))->render()
        ]);
    }

    /**
     * Get products with high discount
     */
    public function flashSale()
    {
        $products = Product::with(['primaryImage', 'images'])
            ->where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->whereNotNull('discount_price')
            ->whereColumn('discount_price', '<', 'base_price')
            ->orderByRaw('(base_price - discount_price) desc') // Sort by discount amount
            ->paginate(16);

        return view('flash-sale', compact('products'));
    }

    /**
     * Get new arrivals
     */
    public function newArrivals()
    {
        $products = Product::with(['primaryImage', 'images'])
            ->where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->orderBy('created_at', 'desc')
            ->paginate(16);

        return view('new-arrivals', compact('products'));
    }
    /**
     * Display a specific resource.
     * This might be what your route is trying to call
     */
    public function show($id)
    {
        // If this is for products
        if (request()->is('products/*')) {
            return $this->productShow($id);
        }

        // If this is for categories
        if (request()->is('category/*')) {
            return $this->categoryShow($id);
        }

        abort(404);
    }
    /**
     * AJAX: Check if product has attributes by hashid
     */
    public function checkProductAttribute(Request $request)
    {
        $product = \App\Models\Product::findByHashid($request->product_id);
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }
        $hasAttribute = $product->attributesRows()->count() > 0;
        return response()->json([
            'has_attribute' => $hasAttribute,
            'slug' => $product->slug,
        ]);
    }
}

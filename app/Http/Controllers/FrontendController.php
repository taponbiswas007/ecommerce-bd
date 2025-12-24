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
        // Get featured products
        $featuredProducts = Product::with(['images', 'category'])
            ->where('is_featured', true)
            ->where('is_active', true)
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
        $flashSaleProducts = Product::with('images')
            ->where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->whereNotNull('discount_price')
            ->whereColumn('discount_price', '<', 'base_price') // Using whereColumn for comparison
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // Get new arrivals
        $newArrivals = Product::with('images')
            ->where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();
        $dealProduct = Product::with('primaryImage')
            ->where('is_deal', 1)
            ->where('is_active', 1)
            ->where('stock_quantity', '>', 0)
            ->where('deal_end_at', '>=', now())
            ->latest('deal_end_at')
            ->first();


        return view('home', compact(
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
        $query = Product::with(['images', 'category'])
            ->where('is_active', true)
            ->where('stock_quantity', '>', 0);

        // Search filter
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('short_description', 'like', '%' . $request->search . '%')
                    ->orWhere('full_description', 'like', '%' . $request->search . '%');
            });
        }

        // Category filter
        if ($request->has('category') && $request->category) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Price filter
        if ($request->has('min_price') && $request->min_price) {
            $query->where('base_price', '>=', $request->min_price);
        }
        if ($request->has('max_price') && $request->max_price) {
            $query->where('base_price', '<=', $request->max_price);
        }

        // Sort by
        $sortBy = $request->get('sort_by', 'newest');
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('base_price');
                break;
            case 'price_high':
                $query->orderByDesc('base_price');
                break;
            case 'name':
                $query->orderBy('name');
                break;
            case 'popular':
                $query->orderByDesc('view_count');
                break;
            case 'discount':
                $query->whereNotNull('discount_price')
                    ->whereColumn('discount_price', '<', 'base_price')
                    ->orderByRaw('(base_price - discount_price) desc');
                break;
            default:
                $query->orderByDesc('created_at');
        }

        $products = $query->paginate(12);
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

        // Get all child category IDs including parent
        $categoryIds = $category->children->pluck('id')->push($category->id);

        $products = Product::with('images')
            ->whereIn('category_id', $categoryIds)
            ->where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('category.show', compact('category', 'products'));
    }

    /**
     * Quick view product
     */
    public function quickView($id)
    {
        $product = Product::with(['images', 'category'])
            ->where('id', $id)
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
        $products = Product::with('images')
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
        $products = Product::with('images')
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
}

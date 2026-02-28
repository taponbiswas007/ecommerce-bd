<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\AI\AIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AIController extends Controller
{
    protected AIService $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * AI Dashboard
     */
    public function index()
    {
        $stats = $this->getAIStats();

        return view('admin.ai.index', compact('stats'));
    }

    /**
     * AI Chat Interface
     */
    public function chat()
    {
        $recentStats = $this->getRecentStats();

        return view('admin.ai.chat', compact('recentStats'));
    }

    /**
     * Send chat message
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
            'provider' => 'nullable|in:gemini,groq',
            'include_context' => 'nullable|boolean',
        ]);

        $provider = $request->input('provider', config('ai.default_provider'));
        $this->aiService->setProvider($provider);

        $context = [];
        if ($request->boolean('include_context')) {
            $context = $this->getBusinessContext();
        }

        $result = $this->aiService->chat($request->message, $context);

        return response()->json([
            'success' => $result['success'],
            'message' => $result['content'] ?? 'AI response failed',
            'provider' => $provider,
        ]);
    }

    /**
     * Product Description Generator
     */
    public function productDescription()
    {
        $categories = Category::where('is_active', true)->get();
        $products = Product::with('category')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.ai.product-description', compact('categories', 'products'));
    }

    /**
     * Generate Product Description
     */
    public function generateProductDescription(Request $request)
    {
        $request->validate([
            'product_id' => 'nullable|exists:products,id',
            'name' => 'required_without:product_id|string|max:255',
            'category' => 'nullable|string|max:255',
            'price' => 'nullable|numeric',
            'language' => 'nullable|in:bn,en',
            'provider' => 'nullable|in:gemini,groq',
        ]);

        $provider = $request->input('provider', config('ai.default_provider'));
        $this->aiService->setProvider($provider);

        // If product_id provided, get product data
        if ($request->product_id) {
            $product = Product::with('category')->find($request->product_id);
            $productData = [
                'name' => $product->name,
                'category' => $product->category->name ?? 'General',
                'price' => $product->discount_price ?? $product->base_price,
            ];
        } else {
            $productData = [
                'name' => $request->name,
                'category' => $request->category ?? 'General',
                'price' => $request->price ?? 0,
            ];
        }

        $language = $request->input('language', 'bn');
        $result = $this->aiService->generateProductDescription($productData, $language);

        return response()->json([
            'success' => $result['success'],
            'description' => $result['content'] ?? null,
            'error' => $result['error'] ?? null,
            'provider' => $provider,
        ]);
    }

    /**
     * Apply generated description to product
     */
    public function applyProductDescription(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'description' => 'required|string',
            'type' => 'required|in:short,full',
        ]);

        $product = Product::find($request->product_id);

        if ($request->type === 'short') {
            $product->short_description = $request->description;
        } else {
            $product->full_description = $request->description;
        }

        $product->save();

        return response()->json([
            'success' => true,
            'message' => 'Description applied successfully!',
            'product' => $product,
        ]);
    }

    /**
     * Category Description Generator
     */
    public function categoryDescription()
    {
        $categories = Category::with('children')
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->get();

        return view('admin.ai.category-description', compact('categories'));
    }

    /**
     * Generate Category Description
     */
    public function generateCategoryDescription(Request $request)
    {
        $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'name' => 'required_without:category_id|string|max:255',
            'language' => 'nullable|in:bn,en',
            'provider' => 'nullable|in:gemini,groq',
        ]);

        $provider = $request->input('provider', config('ai.default_provider'));
        $this->aiService->setProvider($provider);

        if ($request->category_id) {
            $category = Category::with('children')->find($request->category_id);
            $categoryData = [
                'name' => $category->name,
                'subcategories' => $category->children->pluck('name')->toArray(),
            ];
        } else {
            $categoryData = [
                'name' => $request->name,
                'subcategories' => $request->subcategories ?? [],
            ];
        }

        $language = $request->input('language', 'bn');
        $result = $this->aiService->generateCategoryDescription($categoryData, $language);

        return response()->json([
            'success' => $result['success'],
            'description' => $result['content'] ?? null,
            'error' => $result['error'] ?? null,
            'provider' => $provider,
        ]);
    }

    /**
     * Apply generated description to category
     */
    public function applyCategoryDescription(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
        ]);

        $category = Category::find($request->category_id);
        $category->description = $request->description;
        $category->save();

        return response()->json([
            'success' => true,
            'message' => 'Description applied successfully!',
            'category' => $category,
        ]);
    }

    /**
     * Sales Analysis
     */
    public function salesAnalysis()
    {
        $salesData = $this->getSalesDataForAnalysis();

        return view('admin.ai.sales-analysis', compact('salesData'));
    }

    /**
     * Analyze Sales with AI
     */
    public function analyzeSales(Request $request)
    {
        $request->validate([
            'period' => 'nullable|in:week,month,quarter,year',
            'language' => 'nullable|in:bn,en',
            'provider' => 'nullable|in:gemini,groq',
        ]);

        $provider = $request->input('provider', config('ai.default_provider'));
        $this->aiService->setProvider($provider);

        $period = $request->input('period', 'month');
        $salesData = $this->getSalesDataForAnalysis($period);

        $language = $request->input('language', 'bn');
        $result = $this->aiService->analyzeSales($salesData, $language);

        return response()->json([
            'success' => $result['success'],
            'analysis' => $result['content'] ?? null,
            'error' => $result['error'] ?? null,
            'provider' => $provider,
            'sales_data' => $salesData,
        ]);
    }

    /**
     * Product Recommendations
     */
    public function recommendations()
    {
        $salesData = $this->getSalesDataForAnalysis('month');

        return view('admin.ai.recommendations', compact('salesData'));
    }

    /**
     * Get AI Product Recommendations
     */
    public function getRecommendations(Request $request)
    {
        $request->validate([
            'language' => 'nullable|in:bn,en',
            'provider' => 'nullable|in:gemini,groq',
        ]);

        $provider = $request->input('provider', config('ai.default_provider'));
        $this->aiService->setProvider($provider);

        $salesData = $this->getSalesDataForAnalysis('month');
        $language = $request->input('language', 'bn');

        $result = $this->aiService->getProductRecommendations($salesData, $language);

        return response()->json([
            'success' => $result['success'],
            'recommendations' => $result['content'] ?? null,
            'error' => $result['error'] ?? null,
            'provider' => $provider,
        ]);
    }

    /**
     * SEO Generator
     */
    public function seoGenerator()
    {
        $products = Product::where('is_active', true)
            ->whereNull('meta_title')
            ->orWhereNull('meta_description')
            ->orderBy('name')
            ->get();

        return view('admin.ai.seo-generator', compact('products'));
    }

    /**
     * Generate SEO Meta
     */
    public function generateSeo(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'language' => 'nullable|in:bn,en',
            'provider' => 'nullable|in:gemini,groq',
        ]);

        $provider = $request->input('provider', config('ai.default_provider'));
        $this->aiService->setProvider($provider);

        $product = Product::find($request->product_id);
        $productData = [
            'name' => $product->name,
            'description' => $product->short_description ?? $product->full_description ?? '',
        ];

        $language = $request->input('language', 'bn');
        $result = $this->aiService->generateSeoMeta($productData, $language);

        return response()->json([
            'success' => $result['success'],
            'seo_data' => $result['content'] ?? null,
            'parsed_meta' => $result['parsed_meta'] ?? null,
            'error' => $result['error'] ?? null,
            'provider' => $provider,
        ]);
    }

    /**
     * Apply SEO Meta to Product
     */
    public function applySeo(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
        ]);

        $product = Product::find($request->product_id);

        if ($request->meta_title) {
            $product->meta_title = $request->meta_title;
        }
        if ($request->meta_description) {
            $product->meta_description = $request->meta_description;
        }
        if ($request->meta_keywords) {
            $product->meta_keywords = $request->meta_keywords;
        }

        $product->save();

        return response()->json([
            'success' => true,
            'message' => 'SEO data applied successfully!',
            'product' => $product,
        ]);
    }

    /**
     * AI Settings
     */
    public function settings()
    {
        $config = [
            'default_provider' => config('ai.default_provider'),
            'gemini_configured' => !empty(config('ai.providers.gemini.api_key')),
            'groq_configured' => !empty(config('ai.providers.groq.api_key')),
            'features' => config('ai.features'),
        ];

        return view('admin.ai.settings', compact('config'));
    }

    /**
     * Test AI Connection
     */
    public function testConnection(Request $request)
    {
        $request->validate([
            'provider' => 'required|in:gemini,groq',
        ]);

        $this->aiService->setProvider($request->provider);
        $result = $this->aiService->testConnection();

        return response()->json($result);
    }

    /**
     * Get AI Stats for Dashboard
     */
    protected function getAIStats(): array
    {
        return [
            'total_products' => Product::count(),
            'products_without_description' => Product::whereNull('full_description')
                ->orWhere('full_description', '')
                ->count(),
            'products_without_seo' => Product::whereNull('meta_title')
                ->orWhereNull('meta_description')
                ->count(),
            'categories_without_description' => Category::whereNull('description')
                ->orWhere('description', '')
                ->count(),
            'top_selling_products' => Product::orderBy('sold_count', 'desc')
                ->limit(5)
                ->get(['name', 'sold_count']),
            'low_stock_products' => Product::where('stock_quantity', '<=', 10)
                ->where('is_active', true)
                ->count(),
        ];
    }

    /**
     * Get Recent Stats for Chat Context
     */
    protected function getRecentStats(): array
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        return [
            'today_orders' => Order::whereDate('created_at', $today)->count(),
            'today_revenue' => Order::whereDate('created_at', $today)
                ->where('payment_status', 'paid')
                ->sum('total_amount'),
            'month_orders' => Order::where('created_at', '>=', $thisMonth)->count(),
            'month_revenue' => Order::where('created_at', '>=', $thisMonth)
                ->where('payment_status', 'paid')
                ->sum('total_amount'),
            'pending_orders' => Order::where('order_status', 'pending')->count(),
            'low_stock_count' => Product::where('stock_quantity', '<=', 10)
                ->where('is_active', true)
                ->count(),
        ];
    }

    /**
     * Get Business Context for AI Chat
     */
    protected function getBusinessContext(): array
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        return [
            'today_orders' => Order::whereDate('created_at', $today)->count(),
            'today_revenue' => Order::whereDate('created_at', $today)->sum('total_amount'),
            'month_orders' => Order::where('created_at', '>=', $thisMonth)->count(),
            'month_revenue' => Order::where('created_at', '>=', $thisMonth)->sum('total_amount'),
            'total_products' => Product::where('is_active', true)->count(),
            'total_categories' => Category::where('is_active', true)->count(),
            'pending_orders' => Order::where('order_status', 'pending')->count(),
            'low_stock_products' => Product::where('stock_quantity', '<=', 10)
                ->where('is_active', true)
                ->limit(10)
                ->get(['name', 'stock_quantity'])
                ->toArray(),
            'top_selling' => Product::orderBy('sold_count', 'desc')
                ->limit(5)
                ->get(['name', 'sold_count'])
                ->toArray(),
        ];
    }

    /**
     * Get Sales Data for Analysis
     */
    protected function getSalesDataForAnalysis(string $period = 'month'): array
    {
        $startDate = match ($period) {
            'week' => Carbon::now()->subWeek(),
            'month' => Carbon::now()->subMonth(),
            'quarter' => Carbon::now()->subQuarter(),
            'year' => Carbon::now()->subYear(),
            default => Carbon::now()->subMonth(),
        };

        // Top Products
        $topProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.created_at', '>=', $startDate)
            ->where('orders.payment_status', 'paid')
            ->select(
                'products.name',
                DB::raw('SUM(order_items.quantity) as sold_count'),
                DB::raw('SUM(order_items.quantity * order_items.unit_price) as revenue')
            )
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('sold_count')
            ->limit(10)
            ->get()
            ->toArray();

        // Category Performance
        $categoryPerformance = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.created_at', '>=', $startDate)
            ->where('orders.payment_status', 'paid')
            ->select(
                'categories.name',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.quantity * order_items.unit_price) as revenue')
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('revenue')
            ->limit(10)
            ->get()
            ->toArray();

        // Monthly Sales
        $monthlySales = Order::where('created_at', '>=', $startDate)
            ->where('payment_status', 'paid')
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('SUM(total_amount) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();

        // Low Stock Products
        $lowStock = Product::where('stock_quantity', '<=', 10)
            ->where('is_active', true)
            ->orderBy('stock_quantity')
            ->limit(10)
            ->get(['name', 'stock_quantity as stock'])
            ->toArray();

        return [
            'top_products' => array_map(fn($p) => (array) $p, $topProducts),
            'category_performance' => array_map(fn($c) => (array) $c, $categoryPerformance),
            'monthly_sales' => $monthlySales,
            'low_stock' => $lowStock,
            'period' => $period,
            'start_date' => $startDate->format('Y-m-d'),
        ];
    }
}

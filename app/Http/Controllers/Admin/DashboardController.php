<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Category;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display admin dashboard
     */
    public function index()
    {
        // Get statistics for dashboard
        $stats = $this->getDashboardStats();

        // Get recent orders
        $recentOrders = Order::with('user')
            ->latest()
            ->limit(10)
            ->get();

        // Get top selling products
        $topProducts = Product::with('category')
            ->where('is_active', true)
            ->orderBy('sold_count', 'desc')
            ->limit(10)
            ->get();

        // Get latest reviews
        $recentReviews = Review::with(['product', 'user'])
            ->latest()
            ->limit(10)
            ->get();

        // Get sales data for chart
        $salesData = $this->getSalesChartData();

        // Get order status counts
        $orderStatusCounts = $this->getOrderStatusCounts();

        // Get revenue by category
        $categoryRevenue = $this->getCategoryRevenue();

        return view('admin.dashboard', compact(
            'stats',
            'recentOrders',
            'topProducts',
            'recentReviews',
            'salesData',
            'orderStatusCounts',
            'categoryRevenue'
        ));
    }

    /**
     * Get dashboard statistics
     */
    private function getDashboardStats()
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();

        return [
            // Total Statistics
            'total_orders' => Order::count(),
            'total_products' => Product::where('is_active', true)->count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'total_revenue' => Order::where('payment_status', 'paid')->sum('total_amount'),

            // Today's Statistics
            'today_orders' => Order::whereDate('created_at', $today)->count(),
            'today_revenue' => Order::whereDate('created_at', $today)
                ->where('payment_status', 'paid')
                ->sum('total_amount'),
            'today_customers' => User::where('role', 'customer')
                ->whereDate('created_at', $today)
                ->count(),

            // This Month Statistics
            'month_orders' => Order::where('created_at', '>=', $thisMonth)->count(),
            'month_revenue' => Order::where('created_at', '>=', $thisMonth)
                ->where('payment_status', 'paid')
                ->sum('total_amount'),
            'month_customers' => User::where('role', 'customer')
                ->where('created_at', '>=', $thisMonth)
                ->count(),

            // Yesterday vs Today Comparison
            'yesterday_orders' => Order::whereDate('created_at', $yesterday)->count(),
            'yesterday_revenue' => Order::whereDate('created_at', $yesterday)
                ->where('payment_status', 'paid')
                ->sum('total_amount'),

            // Last Month vs This Month Comparison
            'last_month_orders' => Order::whereBetween('created_at', [$lastMonth, $endOfLastMonth])->count(),
            'last_month_revenue' => Order::whereBetween('created_at', [$lastMonth, $endOfLastMonth])
                ->where('payment_status', 'paid')
                ->sum('total_amount'),

            // Other Stats
            'pending_orders' => Order::where('order_status', 'pending')->count(),
            'pending_reviews' => Review::where('status', 'pending')->count(),
            'low_stock_products' => Product::where('stock_quantity', '<=', 10)
                ->where('is_active', true)
                ->count(),
            'active_categories' => Category::where('is_active', true)->count(),
        ];
    }

    /**
     * Get sales data for chart
     */
    private function getSalesChartData()
    {
        // Get sales for last 30 days
        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now();

        $salesData = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as orders'),
            DB::raw('SUM(total_amount) as revenue')
        )
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Format for chart
        $labels = [];
        $orders = [];
        $revenue = [];

        foreach ($salesData as $data) {
            $labels[] = Carbon::parse($data->date)->format('M d');
            $orders[] = $data->orders;
            $revenue[] = (float) $data->revenue;
        }

        // Fill missing dates with zero
        $completeData = [];
        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {
            $dateStr = $currentDate->format('Y-m-d');
            $labelStr = $currentDate->format('M d');

            $index = array_search($dateStr, array_column($salesData->toArray(), 'date'));

            if ($index !== false) {
                $completeData['labels'][] = $labelStr;
                $completeData['orders'][] = $salesData[$index]->orders;
                $completeData['revenue'][] = (float) $salesData[$index]->revenue;
            } else {
                $completeData['labels'][] = $labelStr;
                $completeData['orders'][] = 0;
                $completeData['revenue'][] = 0;
            }

            $currentDate->addDay();
        }

        return $completeData;
    }

    /**
     * Get order status counts
     */
    private function getOrderStatusCounts()
    {
        $statuses = [
            'pending',
            'confirmed',
            'processing',
            'ready_to_ship',
            'shipped',
            'delivered',
            'completed',
            'cancelled',
            'refunded'
        ];

        $counts = [];

        foreach ($statuses as $status) {
            $counts[$status] = Order::where('order_status', $status)->count();
        }

        return $counts;
    }

    /**
     * Get revenue by category
     */
    private function getCategoryRevenue()
    {
        return Category::select(
            'categories.id',
            'categories.name',
            DB::raw('SUM(order_items.total_price) as revenue'),
            DB::raw('COUNT(DISTINCT orders.id) as orders')
        )
            ->leftJoin('products', 'categories.id', '=', 'products.category_id')
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->leftJoin('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.payment_status', 'paid')
            ->where('categories.is_active', true)
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('revenue', 'desc')
            ->limit(10)
            ->get();
    }

    /**
     * Get dashboard overview data via AJAX
     */
    public function getOverviewData(Request $request)
    {
        $period = $request->get('period', 'today');

        switch ($period) {
            case 'week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                break;
            case 'month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
            case 'year':
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                break;
            case 'today':
            default:
                $startDate = Carbon::today();
                $endDate = Carbon::today()->endOfDay();
                break;
        }

        $data = [
            'orders' => Order::whereBetween('created_at', [$startDate, $endDate])->count(),
            'revenue' => Order::whereBetween('created_at', [$startDate, $endDate])
                ->where('payment_status', 'paid')
                ->sum('total_amount'),
            'customers' => User::where('role', 'customer')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count(),
            'products_sold' => DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->whereBetween('orders.created_at', [$startDate, $endDate])
                ->where('orders.payment_status', 'paid')
                ->sum('order_items.quantity'),
        ];

        return response()->json($data);
    }

    /**
     * Get sales chart data via AJAX
     */
    public function getSalesChart(Request $request)
    {
        $period = $request->get('period', '30days');

        switch ($period) {
            case '7days':
                $days = 7;
                $dateFormat = 'D';
                break;
            case '90days':
                $days = 90;
                $dateFormat = 'M d';
                break;
            case 'year':
                $days = 365;
                $dateFormat = 'M Y';
                break;
            case '30days':
            default:
                $days = 30;
                $dateFormat = 'M d';
                break;
        }

        $startDate = Carbon::now()->subDays($days);
        $endDate = Carbon::now();

        $salesData = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as orders'),
            DB::raw('SUM(total_amount) as revenue')
        )
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = [];
        $orders = [];
        $revenue = [];

        // Create complete data array for all dates in range
        $completeLabels = [];
        $completeOrders = [];
        $completeRevenue = [];

        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {
            $dateStr = $currentDate->format('Y-m-d');
            $labelStr = $currentDate->format($dateFormat);

            $index = $salesData->search(function ($item) use ($dateStr) {
                return $item->date == $dateStr;
            });

            $completeLabels[] = $labelStr;

            if ($index !== false) {
                $completeOrders[] = $salesData[$index]->orders;
                $completeRevenue[] = (float) $salesData[$index]->revenue;
            } else {
                $completeOrders[] = 0;
                $completeRevenue[] = 0;
            }

            $currentDate->addDay();
        }

        return response()->json([
            'labels' => $completeLabels,
            'orders' => $completeOrders,
            'revenue' => $completeRevenue,
        ]);
    }

    /**
     * Get top products data
     */
    public function getTopProducts()
    {
        $products = Product::select(
            'products.id',
            'products.name',
            'products.slug',
            'products.base_price',
            'products.sold_count',
            DB::raw('SUM(order_items.quantity) as total_sold'),
            DB::raw('SUM(order_items.total_price) as total_revenue')
        )
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->leftJoin('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.payment_status', 'paid')
            ->where('products.is_active', true)
            ->groupBy('products.id', 'products.name', 'products.slug', 'products.base_price', 'products.sold_count')
            ->orderBy('total_sold', 'desc')
            ->limit(10)
            ->get();

        return response()->json($products);
    }

    /**
     * Get recent activities
     */
    public function getRecentActivities()
    {
        $activities = [];

        // Recent orders
        $recentOrders = Order::with('user')
            ->latest()
            ->limit(5)
            ->get();

        foreach ($recentOrders as $order) {
            $activities[] = [
                'type' => 'order',
                'title' => 'New Order #' . $order->order_number,
                'description' => 'by ' . $order->user->name,
                'time' => $order->created_at->diffForHumans(),
                'icon' => 'shopping-cart',
                'color' => 'primary',
                'url' => route('admin.orders.show', $order->id),
            ];
        }

        // Recent registrations
        $recentUsers = User::where('role', 'customer')
            ->latest()
            ->limit(5)
            ->get();

        foreach ($recentUsers as $user) {
            $activities[] = [
                'type' => 'user',
                'title' => 'New Customer Registered',
                'description' => $user->name . ' (' . $user->email . ')',
                'time' => $user->created_at->diffForHumans(),
                'icon' => 'user',
                'color' => 'success',
                'url' => route('admin.users.show', $user->id),
            ];
        }

        // Recent reviews
        $recentReviews = Review::with(['product', 'user'])
            ->latest()
            ->limit(5)
            ->get();

        foreach ($recentReviews as $review) {
            $activities[] = [
                'type' => 'review',
                'title' => 'New Product Review',
                'description' => 'for ' . $review->product->name,
                'time' => $review->created_at->diffForHumans(),
                'icon' => 'star',
                'color' => 'warning',
                'url' => route('admin.reviews.index'),
            ];
        }

        // Sort by time (latest first)
        usort($activities, function ($a, $b) {
            return strtotime($b['time']) - strtotime($a['time']);
        });

        // Return only latest 10
        return array_slice($activities, 0, 10);
    }
}

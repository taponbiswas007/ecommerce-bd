@extends('admin.layouts.master')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Overview')
@section('page-subtitle', 'Welcome back, ' . Auth::user()->name . '!')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('page-actions')
    <button class="btn btn-primary">
        <i class="fas fa-plus me-2"></i> Add New Product
    </button>
@endsection

@section('content')
    <!-- Stats Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="dashboard-card">
                <div class="card-icon bg-primary">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="card-title">Total Orders</div>
                <div class="card-value">{{ $stats['total_orders'] }}</div>
                <div
                    class="card-change {{ $stats['month_orders'] >= $stats['last_month_orders'] ? 'positive' : 'negative' }}">
                    <i
                        class="fas fa-arrow-{{ $stats['month_orders'] >= $stats['last_month_orders'] ? 'up' : 'down' }} me-1"></i>
                    {{ abs($stats['month_orders'] - $stats['last_month_orders']) }} from last month
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="dashboard-card">
                <div class="card-icon bg-success">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="card-title">Total Revenue</div>
                <div class="card-value">৳{{ number_format($stats['total_revenue'], 2) }}</div>
                <div
                    class="card-change {{ $stats['month_revenue'] >= $stats['last_month_revenue'] ? 'positive' : 'negative' }}">
                    <i
                        class="fas fa-arrow-{{ $stats['month_revenue'] >= $stats['last_month_revenue'] ? 'up' : 'down' }} me-1"></i>
                    ৳{{ number_format(abs($stats['month_revenue'] - $stats['last_month_revenue']), 2) }} from last month
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="dashboard-card">
                <div class="card-icon bg-warning">
                    <i class="fas fa-users"></i>
                </div>
                <div class="card-title">Total Customers</div>
                <div class="card-value">{{ $stats['total_customers'] }}</div>
                <div class="card-change positive">
                    <i class="fas fa-arrow-up me-1"></i>
                    {{ $stats['month_customers'] }} this month
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="dashboard-card">
                <div class="card-icon bg-danger">
                    <i class="fas fa-box"></i>
                </div>
                <div class="card-title">Total Products</div>
                <div class="card-value">{{ $stats['total_products'] }}</div>
                <div class="d-flex align-items-center mt-2">
                    <span class="badge {{ $stats['low_stock_products'] > 0 ? 'bg-danger' : 'bg-success' }} me-2">
                        {{ $stats['low_stock_products'] }} low stock
                    </span>
                    <span class="badge bg-info">
                        {{ $stats['active_categories'] }} categories
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <div class="col-xl-8 mb-4">
            <div class="chart-container">
                <div class="chart-header">
                    <h5>Sales Overview</h5>
                    <div class="chart-actions">
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-outline-secondary active"
                                onclick="updateChart('7days')">7D</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                onclick="updateChart('30days')">30D</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                onclick="updateChart('90days')">90D</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                onclick="updateChart('year')">1Y</button>
                        </div>
                    </div>
                </div>
                <canvas id="salesChart" height="250"></canvas>
            </div>
        </div>

        <div class="col-xl-4 mb-4">
            <div class="chart-container">
                <div class="chart-header">
                    <h5>Order Status</h5>
                </div>
                <canvas id="orderStatusChart" height="250"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Orders & Top Products -->
    <div class="row">
        <div class="col-xl-8 mb-4">
            <div class="data-table">
                <div class="table-header">
                    <h5>Recent Orders</h5>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">
                        View All <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="table-container">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                                <tr>
                                    <td><strong>#{{ $order->order_number }}</strong></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-primary me-2"
                                                style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; color: white; font-size: 12px;">
                                                {{ strtoupper(substr($order->user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $order->user->name }}</div>
                                                <small class="text-muted">{{ $order->user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                                    <td class="fw-bold">৳{{ number_format($order->total_amount, 2) }}</td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'pending' => 'warning',
                                                'confirmed' => 'info',
                                                'processing' => 'primary',
                                                'ready_to_ship' => 'info',
                                                'shipped' => 'primary',
                                                'delivered' => 'success',
                                                'completed' => 'success',
                                                'cancelled' => 'danger',
                                                'refunded' => 'danger',
                                            ];
                                            $statusLabels = [
                                                'pending' => 'Pending',
                                                'confirmed' => 'Confirmed',
                                                'processing' => 'Processing',
                                                'ready_to_ship' => 'Ready to Ship',
                                                'shipped' => 'Shipped',
                                                'delivered' => 'Delivered',
                                                'completed' => 'Completed',
                                                'cancelled' => 'Cancelled',
                                                'refunded' => 'Refunded',
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$order->order_status] ?? 'secondary' }}">
                                            {{ $statusLabels[$order->order_status] ?? ucfirst($order->order_status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order->id) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="fas fa-shopping-cart fa-2x text-muted mb-3"></i>
                                        <h5>No orders yet</h5>
                                        <p class="text-muted">Start selling to see orders here</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-xl-4 mb-4">
            <div class="data-table">
                <div class="table-header">
                    <h5>Top Selling Products</h5>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-primary">
                        View All <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="table-container">
                    <div class="list-group list-group-flush">
                        @forelse($topProducts as $product)
                            <a href="{{ route('admin.products.show', $product->id) }}"
                                class="list-group-item list-group-item-action border-0 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <img src="{{ $product->primary_image ? asset('storage/' . $product->primary_image->image_path) : 'https://via.placeholder.com/50' }}"
                                            alt="{{ $product->name }}" class="rounded"
                                            style="width: 50px; height: 50px; object-fit: cover;">
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0">{{ Str::limit($product->name, 25) }}</h6>
                                            <span class="badge bg-primary">{{ $product->sold_count }} sold</span>
                                        </div>
                                        <small
                                            class="text-muted">{{ $product->category->name ?? 'Uncategorized' }}</small>
                                        <div class="mt-1">
                                            <span class="fw-bold">৳{{ number_format($product->base_price, 2) }}</span>
                                            @if ($product->has_discount)
                                                <small
                                                    class="text-danger ms-2"><s>৳{{ number_format($product->discount_price, 2) }}</s></small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="text-center py-4">
                                <i class="fas fa-box fa-2x text-muted mb-3"></i>
                                <h5>No products yet</h5>
                                <p class="text-muted">Add products to see them here</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row">
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card border-0 bg-primary bg-gradient text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Pending Orders</h6>
                            <h2 class="mb-0">{{ $stats['pending_orders'] }}</h2>
                        </div>
                        <div class="rounded-circle bg-white p-3">
                            <i class="fas fa-clock text-primary fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card border-0 bg-warning bg-gradient text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Pending Reviews</h6>
                            <h2 class="mb-0">{{ $stats['pending_reviews'] }}</h2>
                        </div>
                        <div class="rounded-circle bg-white p-3">
                            <i class="fas fa-star text-warning fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card border-0 bg-danger bg-gradient text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Low Stock</h6>
                            <h2 class="mb-0">{{ $stats['low_stock_products'] }}</h2>
                        </div>
                        <div class="rounded-circle bg-white p-3">
                            <i class="fas fa-exclamation-triangle text-danger fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card border-0 bg-success bg-gradient text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Today's Revenue</h6>
                            <h2 class="mb-0">৳{{ number_format($stats['today_revenue'], 2) }}</h2>
                        </div>
                        <div class="rounded-circle bg-white p-3">
                            <i class="fas fa-dollar-sign text-success fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Sales Chart
        let salesChart;

        function initSalesChart() {
            const ctx = document.getElementById('salesChart');
            if (!ctx) return;

            const salesData = @json($salesData);

            salesChart = new Chart(ctx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: salesData.labels || [],
                    datasets: [{
                            label: 'Orders',
                            data: salesData.orders || [],
                            borderColor: '#4361ee',
                            backgroundColor: 'rgba(67, 97, 238, 0.1)',
                            tension: 0.4,
                            fill: true,
                            borderWidth: 2
                        },
                        {
                            label: 'Revenue (৳)',
                            data: salesData.revenue || [],
                            borderColor: '#06d6a0',
                            backgroundColor: 'rgba(6, 214, 160, 0.1)',
                            tension: 0.4,
                            fill: true,
                            borderWidth: 2
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: 'rgba(0, 0, 0, 0.7)',
                            padding: 10,
                            titleFont: {
                                size: 14
                            },
                            bodyFont: {
                                size: 13
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                maxRotation: 45
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                borderDash: [2, 4]
                            }
                        }
                    }
                }
            });
        }

        // Order Status Chart
        let orderStatusChart;

        function initOrderStatusChart() {
            const ctx = document.getElementById('orderStatusChart');
            if (!ctx) return;

            const statusData = {
                labels: [],
                data: [],
                colors: []
            };

            @foreach ($orderStatusCounts as $status => $count)
                @if ($count > 0)
                    statusData.labels.push("{{ ucfirst(str_replace('_', ' ', $status)) }}");
                    statusData.data.push({{ $count }});

                    @php
                        $color = match ($status) {
                            'pending' => '#ffd166',
                            'confirmed' => '#118ab2',
                            'processing' => '#4361ee',
                            'ready_to_ship' => '#7209b7',
                            'shipped' => '#f72585',
                            'delivered' => '#06d6a0',
                            'completed' => '#0d9488',
                            'cancelled' => '#ef476f',
                            'refunded' => '#6c757d',
                            default => '#adb5bd',
                        };
                    @endphp
                    statusData.colors.push("{{ $color }}");
                @endif
            @endforeach

            orderStatusChart = new Chart(ctx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: statusData.labels,
                    datasets: [{
                        data: statusData.data,
                        backgroundColor: statusData.colors,
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                                boxWidth: 10
                            }
                        }
                    },
                    cutout: '70%'
                }
            });
        }

        // Update chart based on period
        function updateChart(period) {
            fetch(`/admin/dashboard/sales-chart?period=${period}`)
                .then(response => response.json())
                .then(data => {
                    if (salesChart && data.labels) {
                        salesChart.data.labels = data.labels;
                        salesChart.data.datasets[0].data = data.orders;
                        salesChart.data.datasets[1].data = data.revenue;
                        salesChart.update();

                        // Update button states
                        document.querySelectorAll('#salesChart').forEach(chart => {
                            const parent = chart.closest('.chart-container');
                            if (parent) {
                                parent.querySelectorAll('.btn-group .btn').forEach(btn => {
                                    btn.classList.remove('active');
                                    if (btn.textContent.toLowerCase().includes(period.charAt(0))) {
                                        btn.classList.add('active');
                                    }
                                });
                            }
                        });
                    }
                })
                .catch(error => {
                    console.error('Error updating chart:', error);
                });
        }

        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', function() {
            initSalesChart();
            initOrderStatusChart();

            // Auto-refresh data every 5 minutes
            setInterval(() => {
                updateChart('30days');
            }, 300000);
        });
    </script>
@endpush

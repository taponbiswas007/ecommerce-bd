@extends('layouts.app')

@section('styles')
    <style>
        .dashboard-card {
            border: none;
            border-radius: 15px;
            transition: all 0.3s ease;
            overflow: hidden;
            height: 100%;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
        }

        .card-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .bg-gradient-success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }

        .bg-gradient-warning {
            background: linear-gradient(135deg, #f7971e 0%, #ffd200 100%);
        }

        .bg-gradient-info {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .bg-gradient-danger {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .bg-gradient-dark {
            background: linear-gradient(135deg, #434343 0%, #000000 100%);
        }

        .stats-card {
            border-radius: 12px;
            border: none;
            padding: 20px;
            margin-bottom: 20px;
        }

        .welcome-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            overflow: hidden;
        }

        .quick-action-btn {
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid #e9ecef;
            background: white;
            text-decoration: none;
            color: #333;
            display: block;
            height: 100%;
        }

        .quick-action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            color: #333;
            text-decoration: none;
        }

        .quick-action-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 20px;
        }

        .recent-activity-item {
            padding: 15px;
            border-bottom: 1px solid #f1f1f1;
            transition: background 0.2s ease;
        }

        .recent-activity-item:hover {
            background-color: #f8f9fa;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }

        .badge-pill {
            border-radius: 20px;
            padding: 5px 12px;
            font-weight: 500;
        }

        .greeting-text {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .greeting-subtext {
            font-size: 1rem;
            opacity: 0.9;
        }

        @media (max-width: 768px) {
            .greeting-text {
                font-size: 1.5rem;
            }

            .dashboard-card {
                margin-bottom: 20px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container py-4">
        <!-- Welcome Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="welcome-card p-4 p-md-5">
                    <div class="row align-items-center">
                        <div class="col-md-8 mb-4 mb-md-0">
                            <div class="greeting-text">
                                Welcome back, {{ Auth::user()->name }}! 👋
                            </div>
                            <div class="greeting-subtext">
                                Here's what's happening with your account today.
                            </div>
                            <div class="mt-4">
                                <span class="badge bg-light text-dark me-2">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    {{ now()->format('l, F j, Y') }}
                                </span>
                                <span class="badge bg-light text-dark">
                                    <i class="fas fa-clock me-1"></i>
                                    Last login:
                                    {{ Auth::user()->last_login_at ? Auth::user()->last_login_at->diffForHumans() : 'First time' }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4 text-center text-md-end">
                            <div class="position-relative d-inline-block">
                                <div class="bg-white bg-opacity-20 rounded-circle p-4">
                                    <i class="fas fa-user-circle fa-4x text-white"></i>
                                </div>
                                @if (Auth::user()->email_verified_at)
                                    <span
                                        class="position-absolute bottom-0 end-0 bg-success rounded-circle p-1 border border-3 border-white">
                                        <i class="fas fa-check text-white" style="font-size: 12px;"></i>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card dashboard-card shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="text-muted mb-2">Total Orders</h6>
                                <h3 class="fw-bold mb-0">{{ $ordersCount ?? 0 }}</h3>
                                <div class="mt-2">
                                    <span class="text-success">
                                        <i class="fas fa-arrow-up me-1"></i>
                                        12% from last month
                                    </span>
                                </div>
                            </div>
                            <div class="card-icon bg-gradient-primary text-white">
                                <i class="fas fa-shopping-bag"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('orders.index') }}" class="text-primary text-decoration-none">
                                View all orders <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card dashboard-card shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="text-muted mb-2">Pending Orders</h6>
                                <h3 class="fw-bold mb-0">{{ $orders->where('order_status', 'pending')->count() }}</h3>
                                <div class="mt-2">
                                    <span class="text-warning">
                                        <i class="fas fa-clock me-1"></i>
                                        Need your attention
                                    </span>
                                </div>
                            </div>
                            <div class="card-icon bg-gradient-warning text-white">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('orders.index') }}?status=pending" class="text-warning text-decoration-none">
                                View pending <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card dashboard-card shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="text-muted mb-2">Wishlist Items</h6>
                                <h3 class="fw-bold mb-0">{{ $wishlistCount ?? 0 }}</h3>
                                <div class="mt-2">
                                    <a href="{{ route('orders.index') }}" class="text-primary text-decoration-none">
                                        <i class="fas fa-heart me-1"></i>
                                        Saved for later
                                        </span>
                                </div>
                            </div>
                            <div class="card-icon bg-gradient-danger text-white">
                                <i class="fas fa-heart"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('wishlist.index') }}" class="text-danger text-decoration-none">
                                View wishlist <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card dashboard-card shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="text-muted mb-2">Total Spent</h6>
                                <h3 class="fw-bold mb-0">৳{{ number_format($ordersTotal ?? 0, 2) }}</h3>
                                <div class="mt-2">
                                    <a href="{{ route('orders.index') }}?status=pending"
                                        class="text-warning text-decoration-none">
                                        <i class="fas fa-chart-line me-1"></i>
                                        Loyal customer
                                        </span>
                                </div>
                            </div>
                            <div class="card-icon bg-gradient-success text-white">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('orders.index') }}" class="text-success text-decoration-none">
                                View spending <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Dashboard Cards -->
        <div class="row">
            <!-- Left Column -->
            <div class="col-lg-8 mb-4">
                <!-- Quick Actions -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-bolt text-warning me-2"></i>
                            Quick Actions
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3 col-sm-6">
                                <a href="{{ route('orders.index') }}" class="quick-action-btn">
                                    <div class="quick-action-icon bg-primary bg-opacity-10 text-primary">
                                        <i class="fas fa-shopping-bag"></i>
                                    </div>
                                    <h6 class="fw-bold mb-1">My Orders</h6>
                                    <p class="text-muted small mb-0">View & track orders</p>
                                </a>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <a href="{{ route('profile.edit') }}" class="quick-action-btn">
                                    <div class="quick-action-icon bg-success bg-opacity-10 text-success">
                                        <i class="fas fa-user-edit"></i>
                                    </div>
                                    <h6 class="fw-bold mb-1">Edit Profile</h6>
                                    <p class="text-muted small mb-0">Update your info</p>
                                </a>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <a href="{{ route('wishlist.index') }}" class="quick-action-btn">
                                    <div class="quick-action-icon bg-danger bg-opacity-10 text-danger">
                                        <i class="fas fa-heart"></i>
                                    </div>
                                    <h6 class="fw-bold mb-1">Wishlist</h6>
                                    <p class="text-muted small mb-0">Saved products</p>
                                </a>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <a href="{{ route('addresses.index') }}" class="quick-action-btn">
                                    <div class="quick-action-icon bg-info bg-opacity-10 text-info">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <h6 class="fw-bold mb-1">Addresses</h6>
                                    <p class="text-muted small mb-0">Manage addresses</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-history text-primary me-2"></i>
                            Recent Orders
                        </h5>
                        <a href="{{ route('orders.index') }}" class="text-primary text-decoration-none">
                            View All <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                    <div class="card-body p-0">
                        @if ($orders && $orders->count())
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-4">Order ID</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Amount</th>
                                            <th class="pe-4 text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orders as $order)
                                            <tr>
                                                <td class="ps-4">
                                                    <strong>#{{ $order->order_number }}</strong>
                                                </td>
                                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                                <td>
                                                    @php
                                                        $statusColors = [
                                                            'pending' => 'warning',
                                                            'processing' => 'info',
                                                            'shipped' => 'primary',
                                                            'completed' => 'success',
                                                            'cancelled' => 'danger',
                                                        ];
                                                        $statusColor =
                                                            $statusColors[$order->order_status] ?? 'secondary';
                                                    @endphp
                                                    <span
                                                        class="badge bg-{{ $statusColor }} bg-opacity-10 text-{{ $statusColor }} badge-pill">
                                                        {{ ucfirst($order->order_status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <strong>৳{{ number_format($order->total_amount, 2) }}</strong>
                                                </td>
                                                <td class="pe-4 text-end">
                                                    <a href="{{ route('customer.orders.show', $order->id) }}"
                                                        class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye me-1"></i> View
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No orders yet</h5>
                                <p class="text-muted">Start shopping to see your orders here</p>
                                <a href="{{ route('home') }}" class="btn btn-primary mt-2">
                                    <i class="fas fa-shopping-cart me-2"></i> Start Shopping
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-lg-4 mb-4">
                <!-- Profile Summary -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-user-circle text-info me-2"></i>
                            Profile Summary
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <div class="position-relative d-inline-block mb-3">
                                <div class="bg-gradient-info rounded-circle p-3">
                                    <i class="fas fa-user fa-3x text-white"></i>
                                </div>
                                @if (Auth::user()->email_verified_at)
                                    <span
                                        class="position-absolute bottom-0 end-0 bg-success rounded-circle p-1 border border-3 border-white">
                                        <i class="fas fa-check text-white" style="font-size: 12px;"></i>
                                    </span>
                                @endif
                            </div>
                            <h5 class="fw-bold mb-1">{{ Auth::user()->name }}</h5>
                            <p class="text-muted">{{ Auth::user()->email }}</p>
                            @if (!Auth::user()->email_verified_at)
                                <div class="alert alert-warning small mb-3">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Please verify your email address
                                </div>
                            @endif
                        </div>

                        <div class="list-group list-group-flush">
                            <div
                                class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 py-2">
                                <span class="text-muted">
                                    <i class="fas fa-phone me-2"></i> Phone
                                </span>
                                <span class="fw-medium">{{ Auth::user()->phone ?? 'Not set' }}</span>
                            </div>
                            <div
                                class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 py-2">
                                <span class="text-muted">
                                    <i class="fas fa-calendar-alt me-2"></i> Member since
                                </span>
                                <span class="fw-medium">{{ Auth::user()->created_at->format('M Y') }}</span>
                            </div>
                            <div
                                class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 py-2">
                                <span class="text-muted">
                                    <i class="fas fa-map-marker-alt me-2"></i> Addresses
                                </span>
                                <span class="fw-medium">{{ $profile['address'] ?? 'Not set' }}</span>
                            </div>
                            <div
                                class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 py-2">
                                <span class="text-muted">
                                    <i class="fas fa-star me-2"></i> Reviews
                                </span>
                                <span class="fw-medium">0</span>
                            </div>
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-edit me-2"></i> Edit Profile
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-bell text-warning me-2"></i>
                            Recent Activity
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <!-- Default activities -->
                            <div class="recent-activity-item d-flex align-items-center">
                                <div class="activity-icon bg-primary bg-opacity-10 text-primary">
                                    <i class="fas fa-user-check"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Profile updated</h6>
                                    <p class="text-muted small mb-0">2 days ago</p>
                                </div>
                            </div>
                            <div class="recent-activity-item d-flex align-items-center">
                                <div class="activity-icon bg-success bg-opacity-10 text-success">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Order placed</h6>
                                    <p class="text-muted small mb-0">3 days ago</p>
                                </div>
                            </div>
                            <div class="recent-activity-item d-flex align-items-center">
                                <div class="activity-icon bg-info bg-opacity-10 text-info">
                                    <i class="fas fa-heart"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Item added to wishlist</h6>
                                    <p class="text-muted small mb-0">1 week ago</p>
                                </div>
                            </div>
                            <div class="recent-activity-item d-flex align-items-center">
                                <div class="activity-icon bg-warning bg-opacity-10 text-warning">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Address updated</h6>
                                    <p class="text-muted small mb-0">2 weeks ago</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Support Section -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h5 class="fw-bold mb-2">
                                    <i class="fas fa-headset text-primary me-2"></i>
                                    Need Help?
                                </h5>
                                <p class="text-muted mb-0">
                                    Our customer support team is available 24/7 to assist you with any questions or
                                    concerns.
                                </p>
                            </div>
                            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                <a href="{{ route('contact') }}" class="btn btn-primary me-2">
                                    <i class="fas fa-envelope me-2"></i> Contact Support
                                </a>
                                <!-- FAQ button removed: route not found -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Update greeting based on time of day
            const hour = new Date().getHours();
            const greetingElement = document.querySelector('.greeting-text');
            let greeting = 'Welcome back';

            if (hour < 12) {
                greeting = 'Good morning';
            } else if (hour < 18) {
                greeting = 'Good afternoon';
            } else {
                greeting = 'Good evening';
            }

            if (greetingElement) {
                greetingElement.innerHTML = `${greeting}, {{ Auth::user()->name }}! 👋`;
            }

            // Add animation to cards on load
            const cards = document.querySelectorAll('.dashboard-card, .quick-action-btn');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';

                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });

            // Quick action button hover effect
            const quickActionBtns = document.querySelectorAll('.quick-action-btn');
            quickActionBtns.forEach(btn => {
                btn.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px)';
                });

                btn.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        });
    </script>
@endsection

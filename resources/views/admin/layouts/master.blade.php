<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Dashboard') | {{ config('app.name') }}</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- google font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- SimpleBar (custom scrollbar) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simplebar@latest/dist/simplebar.min.css" />

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/style.css') }}">

    <!-- Preloader Styles -->
    <style>
        /* Fix z-index hierarchy for modals */
        .admin-sidebar {
            z-index: 1000 !important;
        }

        .admin-header {
            z-index: 1010 !important;
        }

        .modal-backdrop {
            z-index: 1040 !important;
        }

        .modal {
            z-index: 1055 !important;
        }

        .modal-dialog {
            z-index: 1056 !important;
        }

        .modal-content {
            z-index: 1057 !important;
        }

        .swal2-container {
            z-index: 10000 !important;
        }

        .preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.6s ease, visibility 0.6s ease;
        }

        .preloader.hidden {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }

        .preloader-content {
            text-align: center;
        }

        .preloader-logo {
            margin-bottom: 20px;
            animation: pulse 2s ease-in-out infinite;
        }

        .preloader-logo i {
            font-size: 4rem;
            color: #ffffff;
            text-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        .preloader-logo img {
            width: 120px;
            height: auto;
            object-fit: contain;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }
        }

        .preloader-spinner {
            width: 80px;
            height: 80px;
            margin: 0 auto 25px;
            position: relative;
        }

        .preloader-spinner::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 3px solid rgba(255, 255, 255, 0.2);
            box-sizing: border-box;
            inset: 2px;
        }

        .preloader-spinner::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 3px solid transparent;
            border-top-color: #ffffff;
            border-right-color: #ffffff;
            box-sizing: border-box;
            inset: 2px;
            animation: spin 1s cubic-bezier(0.68, -0.55, 0.27, 1.55) infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .preloader-percentage {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 1rem;
            font-weight: 700;
            color: #ffffff;
            text-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        }

        .preloader-text {
            font-size: 1.2rem;
            color: #ffffff;
            font-weight: 600;
            letter-spacing: 1px;
            margin-bottom: 10px;
            text-transform: uppercase;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        }

        .preloader-dots {
            display: inline-block;
            width: 20px;
            text-align: left;
        }

        .preloader-dots::after {
            content: '';
            animation: dots 1.5s steps(4, end) infinite;
        }

        @keyframes dots {

            0%,
            20% {
                content: '';
            }

            40% {
                content: '.';
            }

            60% {
                content: '..';
            }

            80%,
            100% {
                content: '...';
            }
        }

        .preloader-subtext {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 20px;
        }

        .preloader-progress {
            width: 250px;
            height: 6px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            margin: 0 auto;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .preloader-progress-bar {
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, #ffffff 0%, #f0f0f0 100%);
            border-radius: 10px;
            transition: width 0.3s ease;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Preloader -->
    <div class="preloader" id="preloader">
        <div class="preloader-content">
            <div class="preloader-logo">
                @php
                    $companyLogo = auth()->user()->company_logo ?? null;
                    $companyName = auth()->user()->company_name ?? 'EcommerceBD';
                @endphp
                @if ($companyLogo)
                    <img src="{{ asset('storage/' . $companyLogo) }}" alt="Company Logo">
                @else
                    <img src="{{ asset('assets/images/LOGO.webp') }}" alt="EcommerceBD Logo">
                @endif
            </div>

            <div class="preloader-spinner">
                <div class="preloader-percentage" id="percentage">0%</div>
            </div>

            <div class="preloader-text">
                Loading<span class="preloader-dots"></span>
            </div>

            <p class="preloader-subtext">Please wait while we prepare your dashboard</p>

            <div class="preloader-progress">
                <div class="preloader-progress-bar" id="progressBar"></div>
            </div>
        </div>
    </div>

    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="sidebar-brand">
                <div class="logo flex-shrink-0">
                    @if ($companyLogo)
                        <img src="{{ asset('storage/' . $companyLogo) }}" alt="Company Logo">
                    @else
                        <img src="{{ asset('assets/images/LOGO.webp') }}" alt="EcommerceBD Logo">
                    @endif
                </div>
                <h3 class="text-wrap">{{ $companyName }}</h3>
                <div class="sidebar-toggle d-lg-none d-flex align-items-center justify-content-center">
                    <i class="fas fa-chevron-left"></i>
                </div>
            </div>

            <nav class="sidebar-menu" data-simplebar>
                <ul>
                    <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i>
                            <span class="menu-text">Dashboard</span>
                        </a>
                    </li>

                    <li class="{{ request()->routeIs('admin.ads.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.ads.index') }}">
                            <i class="fas fa-bullhorn"></i>
                            <span class="menu-text">Ads</span>
                        </a>
                    </li>

                    <li
                        class="has-submenu {{ request()->routeIs('admin.products.*') || request()->routeIs('admin.brands.*') ? 'open' : '' }}">
                        <a href="#">
                            <i class="fas fa-box"></i>
                            <span class="menu-text">Products</span>
                            <span class="menu-arrow"><i class="fas fa-chevron-right"></i></span>
                        </a>
                        <ul
                            class="submenu {{ request()->routeIs('admin.products.*') || request()->routeIs('admin.brands.*') ? 'show' : '' }}">
                            <li class="{{ request()->routeIs('admin.products.index') ? 'active' : '' }}">
                                <a href="{{ route('admin.products.index') }}">All Products</a>
                            </li>
                            <li class="{{ request()->routeIs('admin.products.create') ? 'active' : '' }}">
                                <a href="{{ route('admin.products.create') }}">Add New</a>
                            </li>
                            <li class="{{ request()->routeIs('admin.brands.index') ? 'active' : '' }}">
                                <a href="{{ route('admin.brands.index') }}">Brands</a>
                            </li>

                        </ul>
                    </li>

                    <x-admin.sidebar-item route="admin.orders.index" :activeRoutes="['admin.orders.*']" icon="fas fa-shopping-cart"
                        text="Orders" :badge="\App\Models\Order::where('order_status', 'pending')->count()" badgeClass="bg-danger" />

                    <li class="{{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.customers.index') }}">
                            <i class="fas fa-users"></i>
                            <span class="menu-text">Customers</span>
                        </a>
                    </li>

                    <li class="has-submenu {{ request()->routeIs('admin.categories.*') ? 'open' : '' }}">
                        <a href="#">
                            <i class="fas fa-tags"></i>
                            <span class="menu-text">Categories</span>
                            <span class="menu-arrow"><i class="fas fa-chevron-right"></i></span>
                        </a>
                        <ul class="submenu {{ request()->routeIs('admin.categories.*') ? 'show' : '' }}">
                            <li class="{{ request()->routeIs('admin.categories.index') ? 'active' : '' }}">
                                <a href="{{ route('admin.categories.index') }}">All Categories</a>
                            </li>
                            <li class="{{ request()->routeIs('admin.categories.create') ? 'active' : '' }}">
                                <a href="{{ route('admin.categories.create') }}">Add New</a>
                            </li>
                        </ul>
                    </li>
                    <li class="{{ request()->routeIs('admin.units.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.units.index') }}">
                            <i class="fas fa-balance-scale"></i>
                            <span class="menu-text">Units</span>
                        </a>
                    </li>

                    <li class="has-submenu {{ request()->routeIs('admin.coupons.*') ? 'open' : '' }}">
                        <a href="#">
                            <i class="fas fa-ticket-alt"></i>
                            <span class="menu-text">Coupons</span>
                            <span class="menu-arrow"><i class="fas fa-chevron-right"></i></span>
                        </a>
                        <ul class="submenu {{ request()->routeIs('admin.coupons.*') ? 'show' : '' }}">
                            <li class="{{ request()->routeIs('admin.coupons.index') ? 'active' : '' }}">
                                <a href="{{ route('admin.coupons.index') }}">All Coupons</a>
                            </li>
                            <li class="{{ request()->routeIs('admin.coupons.create') ? 'active' : '' }}">
                                <a href="{{ route('admin.coupons.create') }}">Add New</a>
                            </li>
                        </ul>
                    </li>

                    <li class="{{ request()->routeIs('admin.delivery-charges.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.delivery-charges.index') }}">
                            <i class="fas fa-truck"></i>
                            <span class="menu-text">Delivery Charges</span>
                        </a>
                    </li>

                    <li class="{{ request()->routeIs('admin.transport-companies.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.transport-companies.index') }}">
                            <i class="fas fa-ship"></i>
                            <span class="menu-text">Transport Companies</span>
                        </a>
                    </li>

                    <li class="{{ request()->routeIs('admin.package-rates.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.package-rates.index') }}">
                            <i class="fas fa-list"></i>
                            <span class="menu-text">Package Rates</span>
                        </a>
                    </li>

                    <li class="{{ request()->routeIs('admin.packaging-rules.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.packaging-rules.index') }}">
                            <i class="fas fa-boxes"></i>
                            <span class="menu-text">Packaging Rules</span>
                        </a>
                    </li>

                    <li class="{{ request()->routeIs('admin.shop-to-transport-rates.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.shop-to-transport-rates.index') }}">
                            <i class="fas fa-coins"></i>
                            <span class="menu-text">Shop to Transport Rates</span>
                        </a>
                    </li>

                    <li class="{{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.reviews.index') }}">
                            <i class="fas fa-star"></i>
                            <span class="menu-text">Reviews</span>
                            <span
                                class="badge bg-warning ms-auto">{{ \App\Models\Review::where('status', 'pending')->count() }}</span>
                        </a>
                    </li>

                    <li class="{{ request()->routeIs('admin.vat-ait.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.vat-ait.index') }}">
                            <i class="fas fa-percent"></i>
                            <span class="menu-text">VAT & AIT</span>
                        </a>
                    </li>

                    <li class="has-submenu">
                        <a href="#">
                            <i class="fas fa-chart-bar"></i>
                            <span class="menu-text">Reports</span>
                            <span class="menu-arrow"><i class="fas fa-chevron-right"></i></span>
                        </a>
                        <ul class="submenu">
                            <li><a href="#">Sales Report</a></li>
                            <li><a href="#">Customer Report</a></li>
                            <li><a href="#">Product Report</a></li>
                        </ul>
                    </li>

                    <li class="has-submenu">
                        <a href="#">
                            <i class="fas fa-cog"></i>
                            <span class="menu-text">Settings</span>
                            <span class="menu-arrow"><i class="fas fa-chevron-right"></i></span>
                        </a>
                        <ul class="submenu">
                            <li><a href="#">General</a></li>
                            <li><a href="#">Payment</a></li>
                            <li><a href="#">Shipping</a></li>
                            <li><a href="#">Email</a></li>
                        </ul>
                    </li>

                    <li class="mt-5">
                        <a href="{{ route('home') }}" target="_blank">
                            <i class="fas fa-external-link-alt"></i>
                            <span class="menu-text">View Store</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <!-- Header -->
            <header class="admin-header d-flex align-items-center justify-content-between">
                <div class="header-left">
                    <button class="toggle-sidebar d-lg-none">
                        <i class="fas fa-bars"></i>
                    </button>
                    <button class="fullscreen-toggle d-lg-inline d-none header-icon" title="Toggle Fullscreen">
                        <i class="fas fa-expand"></i>
                    </button>
                    <!-- Add these buttons to header-right section -->
                    <button class="theme-toggle" title="Toggle Theme">
                        <i class="fas fa-moon"></i>
                    </button>
                    <div class="header-search">
                        <i class="fas fa-search"></i>
                        <input type="text" class="form-control"
                            placeholder="Search orders, products, customers...">
                    </div>
                </div>

                <div class="header-right">
                    <!-- Notifications -->
                    <div class="dropdown">
                        <div class="header-icon" data-bs-toggle="dropdown">
                            <i class="fas fa-bell"></i>
                            @php
                                $pendingOrders = \App\Models\Order::where('order_status', 'pending')->count();
                                $pendingReviews = \App\Models\Review::where('status', 'pending')->count();
                                $totalNotifications = $pendingOrders + $pendingReviews;
                            @endphp
                            @if ($totalNotifications > 0)
                                <span class="badge-count">{{ $totalNotifications }}</span>
                            @endif
                        </div>
                        <div class="dropdown-menu dropdown-menu-end p-0" style="width: 300px;">
                            <div class="dropdown-header bg-primary text-white py-3">
                                <h6 class="mb-0">Notifications ({{ $totalNotifications }})</h6>
                            </div>
                            <div class="dropdown-body" style="max-height: 300px;" data-simplebar>
                                @if ($pendingOrders > 0)
                                    <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}"
                                        class="dropdown-item py-3 border-bottom">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <div class="rounded-circle bg-warning p-2">
                                                    <i class="fas fa-shopping-cart text-white"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-0">{{ $pendingOrders }} Pending Orders</h6>
                                                <small class="text-muted">Need your approval</small>
                                            </div>
                                        </div>
                                    </a>
                                @endif

                                @if ($pendingReviews > 0)
                                    <a href="{{ route('admin.reviews.index') }}"
                                        class="dropdown-item py-3 border-bottom">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <div class="rounded-circle bg-info p-2">
                                                    <i class="fas fa-star text-white"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-0">{{ $pendingReviews }} Pending Reviews</h6>
                                                <small class="text-muted">Awaiting moderation</small>
                                            </div>
                                        </div>
                                    </a>
                                @endif

                                @php
                                    $lowStockProducts = \App\Models\Product::where('stock_quantity', '<=', 10)
                                        ->where('is_active', true)
                                        ->count();
                                @endphp
                                @if ($lowStockProducts > 0)
                                    <a href="{{ route('admin.products.index', ['low_stock' => true]) }}"
                                        class="dropdown-item py-3 border-bottom">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <div class="rounded-circle bg-danger p-2">
                                                    <i class="fas fa-exclamation-triangle text-white"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-0">{{ $lowStockProducts }} Low Stock Products</h6>
                                                <small class="text-muted">Need restocking</small>
                                            </div>
                                        </div>
                                    </a>
                                @endif

                                @if ($totalNotifications == 0)
                                    <div class="text-center py-4">
                                        <i class="fas fa-check-circle text-success fa-2x mb-2"></i>
                                        <p class="text-muted mb-0">No new notifications</p>
                                    </div>
                                @endif
                            </div>
                            @if ($totalNotifications > 0)
                                <div class="dropdown-footer text-center py-2">
                                    <a href="#" class="text-primary">View All Notifications</a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Messages -->
                    <div class="dropdown">
                        <div class="header-icon" data-bs-toggle="dropdown">
                            <i class="fas fa-envelope"></i>
                            <span class="badge-count">3</span>
                        </div>
                        <div class="dropdown-menu dropdown-menu-end p-0" style="width: 300px;">
                            <div class="dropdown-header bg-info text-white py-3">
                                <h6 class="mb-0">Messages (3)</h6>
                            </div>
                            <div class="dropdown-body" style="max-height: 300px;" data-simplebar>
                                <a href="#" class="dropdown-item py-3 border-bottom">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="rounded-circle bg-primary p-2">
                                                <span class="text-white">JD</span>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mb-0">John Doe</h6>
                                            <small class="text-muted">When will my order ship?</small>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <small class="text-muted">2 min ago</small>
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="dropdown-item py-3 border-bottom">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="rounded-circle bg-success p-2">
                                                <span class="text-white">SM</span>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mb-0">Sarah Miller</h6>
                                            <small class="text-muted">Product quality issue</small>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <small class="text-muted">1 hour ago</small>
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="dropdown-item py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="rounded-circle bg-warning p-2">
                                                <span class="text-white">RJ</span>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mb-0">Robert Johnson</h6>
                                            <small class="text-muted">Return request #12345</small>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <small class="text-muted">3 hours ago</small>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="dropdown-footer text-center py-2">
                                <a href="#" class="text-primary">View All Messages</a>
                            </div>
                        </div>
                    </div>

                    <!-- User Profile -->
                    <div class="dropdown">
                        <div class="user-profile" data-bs-toggle="dropdown">
                            <div class="user-avatar">
                                @php
                                    $userImage = auth()->user()->user_image ?? null;
                                @endphp
                                @if ($userImage)
                                    <img src="{{ asset('storage/' . $userImage) }}" alt="User Avatar"
                                        style="width: 40px; height: 40px; object-fit: cover; border-radius: 50%;">
                                @else
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                @endif
                            </div>
                            <div class="user-info d-none d-md-block">
                                <h6 class="mb-0">{{ auth()->user()->name }}</h6>
                                <small class=" text-capitalize">{{ auth()->user()->role }}</small>
                            </div>
                            <i class="fas fa-chevron-down ms-2"></i>
                        </div>
                        <div class="dropdown-menu dropdown-menu-end">
                            @if (auth()->user()->role === 'admin')
                                <a href="{{ route('admin.profile.edit') }}" class="dropdown-item">
                                    <i class="fas fa-cog me-2"></i> Settings
                                </a>
                            @else
                                <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                    <i class="fas fa-cog me-2"></i> Settings
                                </a>
                            @endif
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}" class="dropdown-item">
                                @csrf
                                <button type="submit" class="btn btn-link text-decoration-none p-0">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <div class="admin-content" data-simplebar data-simplebar-auto-hide="false">
                <!-- Breadcrumb -->
                <div class="card border shadow-sm mb-4 rounded-1">
                    <div class="card-body p-3">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i
                                            class="fas fa-home"></i></a></li>
                                @yield('breadcrumb')
                            </ol>
                        </nav>
                    </div>
                </div>


                <!-- Page Header -->
                <div class="card border shadow-sm mb-4 rounded-1">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div>
                                <h4 class="mb-1">@yield('page-title', 'Dashboard')</h4>
                                <p class="text-muted mb-0">@yield('page-subtitle', 'Welcome back, ' . Auth::user()->name . '!')</p>
                            </div>
                            <div>
                                @yield('page-actions')
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Main Content -->
                @yield('content')
            </div>
        </main>
    </div>

    @yield('modalpopup')

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- SimpleBar JS -->
    <script src="https://cdn.jsdelivr.net/npm/simplebar@latest/dist/simplebar.min.js"></script>


    <script>
        // Preloader functionality
        (function() {
            const preloader = document.getElementById('preloader');
            const progressBar = document.getElementById('progressBar');
            const percentage = document.getElementById('percentage');
            let progress = 0;

            // Simulate loading progress
            const loadingInterval = setInterval(function() {
                if (progress < 90) {
                    progress += Math.random() * 15;
                    if (progress > 90) progress = 90;

                    progressBar.style.width = progress + '%';
                    percentage.textContent = Math.floor(progress) + '%';
                }
            }, 200);

            // On page load complete
            window.addEventListener('load', function() {
                clearInterval(loadingInterval);

                // Complete to 100%
                progress = 100;
                progressBar.style.width = '100%';
                percentage.textContent = '100%';

                // Hide preloader after short delay
                setTimeout(function() {
                    preloader.classList.add('hidden');

                    // Remove from DOM after animation
                    setTimeout(function() {
                        preloader.remove();
                    }, 600);
                }, 400);
            });
        })();

        // DOM Ready
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize SimpleBar on all data-simplebar elements
            const simplebarElements = document.querySelectorAll('[data-simplebar]');
            simplebarElements.forEach(function(el) {
                if (!el.SimpleBar) {
                    new SimpleBar(el, {
                        autoHide: el.getAttribute('data-simplebar-auto-hide') !== 'false',
                        scrollbarMinSize: 25,
                        scrollbarMaxSize: 100
                    });
                }
            });

            // Auto-apply SimpleBar to common scrollable containers
            document.querySelectorAll(
                '.table-responsive, .modal-body, .dropdown-body, .card-body[style*="max-height"]').forEach(
                function(el) {
                    if (!el.hasAttribute('data-simplebar') && !el.SimpleBar) {
                        el.setAttribute('data-simplebar', '');
                        new SimpleBar(el, {
                            autoHide: true,
                            scrollbarMinSize: 25,
                            scrollbarMaxSize: 80
                        });
                    }
                });

            const adminWrapper = document.querySelector('.admin-wrapper');
            const sidebar = document.querySelector('.admin-sidebar');
            const sidebarToggle = document.querySelector('.sidebar-toggle');
            const mobileToggle = document.querySelector('.toggle-sidebar.d-lg-none');
            const desktopToggle = document.querySelector('.toggle-sidebar.d-none.d-lg-inline');
            const themeToggle = document.querySelector('.theme-toggle');
            const fullscreenToggle = document.querySelector('.fullscreen-toggle');

            // Initialize theme
            const currentTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', currentTheme);
            updateThemeIcon(currentTheme);

            // Toggle sidebar visibility (desktop button on sidebar)
            function toggleSidebarVisibility() {
                adminWrapper.classList.toggle('sidebar-hidden');
                sidebar.classList.remove('show');
            }

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    toggleSidebarVisibility();
                });
            }

            // Toggle sidebar visibility (desktop header button)
            if (desktopToggle) {
                desktopToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    toggleSidebarVisibility();
                });
            }

            // Mobile sidebar toggle
            if (mobileToggle) {
                mobileToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    adminWrapper.classList.remove('sidebar-hidden');
                    sidebar.classList.toggle('show');
                });
            }

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(e) {
                if (window.innerWidth <= 992 &&
                    sidebar.classList.contains('show') &&
                    !sidebar.contains(e.target) &&
                    !mobileToggle.contains(e.target)) {
                    sidebar.classList.remove('show');
                }
            });

            // Toggle submenus
            document.querySelectorAll('.has-submenu > a').forEach(function(item) {
                item.addEventListener('click', function(e) {
                    if (window.innerWidth <= 992) {
                        e.preventDefault();
                        return;
                    }

                    const submenu = this.nextElementSibling;
                    const parent = this.parentElement;

                    // Close all other open submenus at the same level
                    const allSubmenus = document.querySelectorAll('.submenu.show');
                    const allParents = document.querySelectorAll('.has-submenu.open');

                    allSubmenus.forEach(function(menu) {
                        if (menu !== submenu && !menu.contains(submenu)) {
                            menu.classList.remove('show');
                        }
                    });

                    allParents.forEach(function(p) {
                        if (p !== parent && !p.contains(parent)) {
                            p.classList.remove('open');
                        }
                    });

                    // Toggle current submenu
                    if (submenu.classList.contains('show')) {
                        submenu.classList.remove('show');
                        parent.classList.remove('open');
                    } else {
                        submenu.classList.add('show');
                        parent.classList.add('open');
                    }
                });
            });

            // Theme toggle
            if (themeToggle) {
                themeToggle.addEventListener('click', function() {
                    const currentTheme = document.documentElement.getAttribute('data-theme');
                    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

                    document.documentElement.setAttribute('data-theme', newTheme);
                    localStorage.setItem('theme', newTheme);
                    updateThemeIcon(newTheme);

                    // Dispatch theme change event
                    window.dispatchEvent(new CustomEvent('themeChanged', {
                        detail: newTheme
                    }));
                });
            }

            function updateThemeIcon(theme) {
                if (!themeToggle) return;

                const icon = themeToggle.querySelector('i');
                if (icon) {
                    icon.className = theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
                }
            }

            // Fullscreen toggle
            if (fullscreenToggle) {
                fullscreenToggle.addEventListener('click', function() {
                    const entering = !adminWrapper.classList.contains('fullscreen');
                    adminWrapper.classList.toggle('fullscreen');
                    if (entering) {
                        adminWrapper.classList.add('sidebar-hidden');
                        sidebar.classList.remove('show');
                    } else {
                        adminWrapper.classList.remove('sidebar-hidden');
                    }

                    const icon = this.querySelector('i');
                    if (adminWrapper.classList.contains('fullscreen')) {
                        icon.className = 'fas fa-compress';
                    } else {
                        icon.className = 'fas fa-expand';
                    }
                });
            }

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 992) {
                    sidebar.classList.remove('show');
                }
            });

            // Initialize SweetAlert
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });

            // Flash Messages
            @if (session('success'))
                Toast.fire({
                    icon: 'success',
                    title: '{{ session('success') }}',
                    background: getComputedStyle(document.documentElement).getPropertyValue('--bs-body-bg'),
                    color: getComputedStyle(document.documentElement).getPropertyValue('--bs-body-color')
                });
            @endif

            @if (session('error'))
                Toast.fire({
                    icon: 'error',
                    title: '{{ session('error') }}',
                    background: getComputedStyle(document.documentElement).getPropertyValue('--bs-body-bg'),
                    color: getComputedStyle(document.documentElement).getPropertyValue('--bs-body-color')
                });
            @endif

            @if (session('warning'))
                Toast.fire({
                    icon: 'warning',
                    title: '{{ session('warning') }}',
                    background: getComputedStyle(document.documentElement).getPropertyValue('--bs-body-bg'),
                    color: getComputedStyle(document.documentElement).getPropertyValue('--bs-body-color')
                });
            @endif

            @if (session('info'))
                Toast.fire({
                    icon: 'info',
                    title: '{{ session('info') }}',
                    background: getComputedStyle(document.documentElement).getPropertyValue('--bs-body-bg'),
                    color: getComputedStyle(document.documentElement).getPropertyValue('--bs-body-color')
                });
            @endif
        });
    </script>

    @stack('scripts')

    <!-- Pusher & Laravel Echo for Real-Time Chat -->
    @auth
        @if (auth()->user()->hasRole('admin'))
            <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.3/dist/echo.iife.js"></script>
            <script>
                // Initialize Laravel Echo for real-time broadcasting
                if (typeof Pusher !== 'undefined' && '{{ config('broadcasting.default') }}' === 'pusher') {
                    window.Pusher = Pusher;

                    window.Echo = new Echo({
                        broadcaster: 'pusher',
                        key: '{{ config('broadcasting.connections.pusher.key') }}',
                        cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}',
                        forceTLS: true,
                        auth: {
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        }
                    });

                    // Function to setup chat listener dynamically for admin
                    window.setupAdminChatListener = function(chatId) {
                        if (!chatId || !window.Echo) {
                            console.warn('[Admin] Echo not available or chatId missing');
                            return;
                        }

                        // Leave previous channel if exists
                        if (window.activeAdminChatChannel) {
                            window.Echo.leave(window.activeAdminChatChannel);
                            console.log('[Admin] Left previous channel:', window.activeAdminChatChannel);
                        }

                        // Setup new chat channel listener
                        window.activeAdminChatChannel = `chat.${chatId}`;
                        console.log('[Admin] Setting up listener for channel:', window.activeAdminChatChannel);

                        Echo.private(window.activeAdminChatChannel)
                            .listen('.message.sent', (e) => {
                                console.log('✅ [Admin] New message received via Pusher:', e);

                                // Only add if this is current chat
                                if (currentAdminChatId === chatId) {
                                    // Check if message already exists (avoid duplicates)
                                    const exists = adminChatMessages.some(m => m.id === e.id);
                                    if (!exists) {
                                        console.log('[Admin] Adding message to current chat');
                                        // Add message to current chat
                                        adminChatMessages.push({
                                            id: e.id,
                                            chat_id: e.chat_id,
                                            user_id: e.user_id,
                                            message: e.message,
                                            created_at: e.created_at || new Date().toISOString(),
                                            user: {
                                                name: e.user_name
                                            }
                                        });

                                        // Re-render messages
                                        renderAdminMessages();
                                        console.log('[Admin] Messages re-rendered');
                                    } else {
                                        console.log('[Admin] Duplicate message ignored');
                                    }
                                } else {
                                    console.log('[Admin] Message for different chat:', e.chat_id);
                                }

                                // Refresh chats list
                                loadAdminChats();

                                // Update unread count
                                fetchAdminUnreadCount();
                            });
                    };

                    // Listen for admin notifications (all chats)
                    Echo.private('user.admin')
                        .listen('.message.sent', (e) => {
                            console.log('Admin notification:', e);

                            // If viewing this chat, add message
                            if (currentAdminChatId && e.chat_id === currentAdminChatId) {
                                const exists = adminChatMessages.some(m => m.id === e.id);
                                if (!exists) {
                                    console.log('✅ [Admin] Adding message from user.admin channel');
                                    adminChatMessages.push({
                                        id: e.id,
                                        chat_id: e.chat_id,
                                        user_id: e.user_id,
                                        message: e.message,
                                        created_at: e.created_at || new Date().toISOString(),
                                        user: {
                                            name: e.user_name
                                        }
                                    });
                                    renderAdminMessages();
                                    console.log('[Admin] Dialog updated with new message');
                                }
                            }

                            // Refresh chats list
                            if (typeof loadAdminChats === 'function') {
                                loadAdminChats();
                            }

                            // Update unread count
                            if (typeof fetchAdminUnreadCount === 'function') {
                                fetchAdminUnreadCount();
                            }

                            // Show notification
                            if (typeof isAdminDialogOpen !== 'undefined' && !isAdminDialogOpen) {
                                Swal.fire({
                                    icon: 'info',
                                    title: 'New Message',
                                    text: 'You have a new customer message',
                                    timer: 3000,
                                    showConfirmButton: false,
                                    position: 'top-end',
                                    toast: true
                                });
                            }
                        });

                    // Listen to user-specific channel
                    Echo.private(`user.{{ auth()->id() }}`)
                        .listen('.message.sent', (e) => {
                            if (typeof fetchAdminUnreadCount === 'function') {
                                fetchAdminUnreadCount();
                            }
                        });
                }
            </script>
        @endif
    @endauth

    <!-- Admin Chat Widget -->
    @auth
        @if (auth()->user()->hasRole('admin'))
            <div id="adminChatWidget" class="admin-chat-widget">
                <!-- Chat Button -->
                <button class="admin-chat-toggle-btn" id="adminChatToggleBtn" onclick="toggleAdminChat()">
                    <i class="fas fa-comments"></i>
                    <span class="admin-chat-unread-badge" id="adminChatUnreadBadge" style="display: none;">0</span>
                </button>

                <!-- Chat Dialog -->
                <div class="admin-chat-dialog" id="adminChatDialog" style="display: none;">
                    <div class="admin-chat-header">
                        <div class="admin-chat-header-title">
                            <i class="fas fa-headset"></i>
                            <span>Customer Chats</span>
                        </div>
                        <button class="admin-chat-close-btn" onclick="toggleAdminChat()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div class="admin-chat-container">
                        <!-- Chats List -->
                        <div class="admin-chats-list" id="adminChatsList">
                            <div class="text-center py-4">
                                <i class="fas fa-spinner fa-spin fa-2x text-blue-600"></i>
                            </div>
                        </div>

                        <!-- Chat Messages -->
                        <div class="admin-chat-messages-container" id="adminChatMessagesContainer"
                            style="display: none;">
                            <div class="admin-chat-messages-header">
                                <button class="admin-chat-back-btn" onclick="backToChats()">
                                    <i class="fas fa-arrow-left"></i>
                                </button>
                                <div class="admin-chat-customer-info" id="adminChatCustomerInfo">
                                    <div class="admin-chat-customer-name">Customer Name</div>
                                </div>
                            </div>

                            <div class="admin-chat-messages" id="adminChatMessages">
                                <!-- Messages will be loaded here -->
                            </div>

                            <div class="admin-chat-footer">
                                <form id="adminChatMessageForm" onsubmit="sendAdminMessage(event)">
                                    <div class="d-flex gap-2">
                                        <input type="text" class="form-control rounded-pill px-4"
                                            id="adminChatMessageInput" placeholder="Type your message..." required
                                            maxlength="5000">
                                        <button class="btn btn-primary rounded-circle"
                                            style="width: 40px; height: 40px; padding: 0;" type="submit">
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Admin Chat CSS -->
            <style>
                .admin-chat-widget {
                    position: fixed;
                    bottom: 20px;
                    right: 20px;
                    z-index: 9999;
                }

                .admin-chat-toggle-btn {
                    width: 45px;
                    height: 45px;
                    border-radius: 50%;
                    background: linear-gradient(135deg, #667eea, #764ba2);
                    border: none;
                    color: white;
                    font-size: 24px;
                    cursor: pointer;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                    position: relative;
                    transition: all 0.3s ease;
                    justify-content: center;
                    align-items: center;
                }

                .admin-chat-toggle-btn:hover {
                    transform: scale(1.1);
                    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
                }

                .admin-chat-unread-badge {
                    position: absolute;
                    top: 0;
                    right: 0;
                    background: #dc2626;
                    color: white;
                    border-radius: 50%;
                    width: 20px;
                    height: 20px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 11px;
                    font-weight: bold;
                    border: 2px solid white;
                }

                .admin-chat-dialog {
                    position: absolute;
                    bottom: 0px;
                    right: 0;
                    width: 450px;
                    max-width: calc(100vw - 40px);
                    height: 550px;
                    max-height: calc(100vh - 120px);
                    background: white;
                    border-radius: 12px;
                    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
                    display: flex;
                    flex-direction: column;
                    overflow: hidden;
                }

                .admin-chat-header {
                    background: linear-gradient(135deg, #667eea, #764ba2);
                    color: white;
                    padding: 16px;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                }

                .admin-chat-header-title {
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    font-weight: 600;
                    font-size: 16px;
                }

                .admin-chat-close-btn {
                    background: transparent;
                    border: none;
                    color: white;
                    font-size: 20px;
                    cursor: pointer;
                    padding: 4px;
                    line-height: 1;
                }

                .admin-chat-container {
                    flex: 1;
                    display: flex;
                    overflow: hidden;
                }

                .admin-chats-list {
                    flex: 1;
                    overflow-y: auto;
                    background: #f8f9fa;
                }

                .admin-chat-item {
                    padding: 12px 16px;
                    border-bottom: 1px solid #e9ecef;
                    cursor: pointer;
                    transition: background 0.2s;
                    display: flex;
                    gap: 12px;
                    align-items: flex-start;
                }

                .admin-chat-item:hover {
                    background: #f1f3f5;
                }

                .admin-chat-item.active {
                    background: #e7f0ff;
                }

                .admin-chat-item-avatar {
                    width: 40px;
                    height: 40px;
                    border-radius: 50%;
                    background: linear-gradient(135deg, #667eea, #764ba2);
                    color: white;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-weight: 600;
                    flex-shrink: 0;
                }

                .admin-chat-item-content {
                    flex: 1;
                    min-width: 0;
                }

                .admin-chat-item-name {
                    font-weight: 600;
                    font-size: 14px;
                    color: #212529;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                }

                .admin-chat-item-last-message {
                    font-size: 13px;
                    color: #6c757d;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    white-space: nowrap;
                    margin-top: 2px;
                }

                .admin-chat-item-time {
                    font-size: 11px;
                    color: #adb5bd;
                }

                .admin-chat-item-unread {
                    background: #ef4444;
                    color: white;
                    border-radius: 50%;
                    width: 20px;
                    height: 20px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 11px;
                    font-weight: bold;
                }

                .admin-chat-messages-container {
                    flex: 1;
                    display: flex;
                    flex-direction: column;
                }

                .admin-chat-messages-header {
                    padding: 12px 16px;
                    border-bottom: 1px solid #e9ecef;
                    background: white;
                    display: flex;
                    align-items: center;
                    gap: 12px;
                }

                .admin-chat-back-btn {
                    background: transparent;
                    border: none;
                    color: #667eea;
                    font-size: 18px;
                    cursor: pointer;
                    padding: 4px;
                }

                .admin-chat-customer-name {
                    font-weight: 600;
                    color: #212529;
                }

                .admin-chat-messages {
                    flex: 1;
                    overflow-y: auto;
                    padding: 16px;
                    background: #f8f9fa;
                    display: flex;
                    flex-direction: column;
                    gap: 12px;
                }

                .admin-chat-message {
                    display: flex;
                    gap: 8px;
                    animation: slideIn 0.3s ease;
                }

                @keyframes slideIn {
                    from {
                        opacity: 0;
                        transform: translateY(10px);
                    }

                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }

                .admin-chat-message.sent {
                    flex-direction: row-reverse;
                }

                .admin-chat-message-avatar {
                    width: 32px;
                    height: 32px;
                    border-radius: 50%;
                    background: linear-gradient(135deg, #667eea, #764ba2);
                    color: white;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 14px;
                    font-weight: 600;
                    flex-shrink: 0;
                }

                .admin-chat-message-content {
                    max-width: 70%;
                }

                .admin-chat-message-bubble {
                    padding: 10px 14px;
                    border-radius: 12px;
                    background: white;
                    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
                    word-wrap: break-word;
                }

                .admin-chat-message.sent .admin-chat-message-bubble {
                    background: linear-gradient(135deg, #667eea, #764ba2);
                    color: white;
                }

                .admin-chat-message-time {
                    font-size: 11px;
                    color: #6c757d;
                    margin-top: 4px;
                    padding: 0 4px;
                }

                .admin-chat-footer {
                    padding: 12px;
                    background: white;
                    border-top: 1px solid #e9ecef;
                }

                @media (max-width: 576px) {
                    .admin-chat-dialog {
                        width: calc(100vw - 40px);
                        height: calc(100vh - 120px);
                    }
                }
            </style>

            <!-- Admin Chat JavaScript -->
            <script>
                let adminChats = [];
                let currentAdminChatId = null;
                let adminChatMessages = [];
                let isAdminDialogOpen = false;

                // Initialize admin chat on page load
                document.addEventListener('DOMContentLoaded', function() {
                    loadAdminChats();
                    fetchAdminUnreadCount();

                    // Poll for updates every 10 seconds
                    setInterval(loadAdminChats, 10000);
                    setInterval(fetchAdminUnreadCount, 10000);
                });

                function toggleAdminChat() {
                    const dialog = document.getElementById('adminChatDialog');
                    const btn = document.getElementById('adminChatToggleBtn');

                    isAdminDialogOpen = !isAdminDialogOpen;

                    if (isAdminDialogOpen) {
                        dialog.style.display = 'flex';
                        btn.style.display = 'none';
                        loadAdminChats();
                    } else {
                        dialog.style.display = 'none';
                        btn.style.display = 'flex';
                    }
                }

                async function loadAdminChats() {
                    try {
                        const response = await fetch('/chat/all', {
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });

                        if (response.ok) {
                            const data = await response.json();
                            adminChats = data.chats;
                            renderAdminChatsList();
                        }
                    } catch (error) {
                        console.error('Error loading admin chats:', error);
                    }
                }

                function renderAdminChatsList() {
                    const container = document.getElementById('adminChatsList');

                    if (adminChats.length === 0) {
                        container.innerHTML = `
                    <div class="text-center text-muted py-8">
                        <i class="fas fa-comments fa-3x mb-3"></i>
                        <p>No customer chats yet</p>
                    </div>
                `;
                        return;
                    }

                    container.innerHTML = adminChats.map(chat => {
                        const initials = chat.customer.name.split(' ').map(n => n[0]).join('').toUpperCase();
                        const lastMessage = chat.latest_message ? chat.latest_message.message : 'No messages yet';
                        const lastMessageTime = chat.last_message_at ?
                            new Date(chat.last_message_at).toLocaleTimeString('en-US', {
                                hour: '2-digit',
                                minute: '2-digit'
                            }) : '';

                        return `
                    <div class="admin-chat-item ${chat.id === currentAdminChatId ? 'active' : ''}" onclick="selectAdminChat(${chat.id})">
                        <div class="admin-chat-item-avatar">${initials}</div>
                        <div class="admin-chat-item-content">
                            <div class="admin-chat-item-name">
                                <span>${escapeHtml(chat.customer.name)}</span>
                                ${chat.unread_count > 0 ? `<span class="admin-chat-item-unread">${chat.unread_count}</span>` : ''}
                            </div>
                            <div class="admin-chat-item-last-message">${escapeHtml(lastMessage.substring(0, 50))}${lastMessage.length > 50 ? '...' : ''}</div>
                            ${lastMessageTime ? `<div class="admin-chat-item-time">${lastMessageTime}</div>` : ''}
                        </div>
                    </div>
                `;
                    }).join('');
                }

                async function selectAdminChat(chatId) {
                    currentAdminChatId = chatId;

                    const chat = adminChats.find(c => c.id === chatId);
                    if (!chat) return;

                    // Show messages container
                    document.getElementById('adminChatsList').style.display = 'none';
                    document.getElementById('adminChatMessagesContainer').style.display = 'flex';

                    // Update customer info
                    document.getElementById('adminChatCustomerInfo').innerHTML = `
                <div class="admin-chat-customer-name">${escapeHtml(chat.customer.name)}</div>
            `;

                    // Setup real-time listener for this chat
                    if (typeof window.setupAdminChatListener === 'function') {
                        window.setupAdminChatListener(chatId);
                    }

                    // Load messages
                    await loadAdminMessages(chatId);
                }

                async function loadAdminMessages(chatId) {
                    try {
                        const response = await fetch(`/chat/${chatId}/messages`, {
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });

                        if (response.ok) {
                            const data = await response.json();
                            adminChatMessages = data.messages;
                            renderAdminMessages();

                            // Refresh chats list to update unread counts
                            loadAdminChats();
                        }
                    } catch (error) {
                        console.error('Error loading admin messages:', error);
                    }
                }

                function renderAdminMessages() {
                    const container = document.getElementById('adminChatMessages');
                    const currentUserId = {{ auth()->id() }};

                    if (adminChatMessages.length === 0) {
                        container.innerHTML = `
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-comments fa-2x mb-2"></i>
                        <p>No messages yet</p>
                    </div>
                `;
                        return;
                    }

                    container.innerHTML = adminChatMessages.map(msg => {
                        const isSent = msg.user_id === currentUserId;
                        const initials = msg.user.name.split(' ').map(n => n[0]).join('').toUpperCase();
                        const time = new Date(msg.created_at).toLocaleTimeString('en-US', {
                            hour: '2-digit',
                            minute: '2-digit'
                        });

                        return `
                    <div class="admin-chat-message ${isSent ? 'sent' : ''}">
                        <div class="admin-chat-message-avatar">${initials}</div>
                        <div class="admin-chat-message-content">
                            <div class="admin-chat-message-bubble">${escapeHtml(msg.message)}</div>
                            <div class="admin-chat-message-time">${time}</div>
                        </div>
                    </div>
                `;
                    }).join('');

                    // Scroll to bottom after DOM updates
                    setTimeout(() => {
                        container.scrollTop = container.scrollHeight;
                    }, 100);
                }

                async function sendAdminMessage(event) {
                    event.preventDefault();

                    if (!currentAdminChatId) return;

                    const input = document.getElementById('adminChatMessageInput');
                    const message = input.value.trim();

                    if (!message) return;

                    try {
                        const response = await fetch(`/chat/${currentAdminChatId}/send`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                message
                            })
                        });

                        if (response.ok) {
                            const data = await response.json();
                            // Ensure created_at field exists
                            if (!data.message.created_at) {
                                data.message.created_at = new Date().toISOString();
                            }
                            adminChatMessages.push(data.message);
                            renderAdminMessages();
                            input.value = '';

                            console.log('[Admin] Message sent successfully');

                            // Refresh chats list
                            loadAdminChats();
                        }
                    } catch (error) {
                        console.error('Error sending admin message:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to send message',
                            timer: 3000,
                            showConfirmButton: false
                        });
                    }
                }

                function backToChats() {
                    document.getElementById('adminChatsList').style.display = 'block';
                    document.getElementById('adminChatMessagesContainer').style.display = 'none';
                    currentAdminChatId = null;
                }

                async function fetchAdminUnreadCount() {
                    try {
                        const response = await fetch('/chat/unread-count', {
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });

                        if (response.ok) {
                            const data = await response.json();
                            updateAdminUnreadBadge(data.unread_count);
                        }
                    } catch (error) {
                        console.error('Error fetching admin unread count:', error);
                    }
                }

                function updateAdminUnreadBadge(count) {
                    const badge = document.getElementById('adminChatUnreadBadge');
                    if (count > 0) {
                        badge.textContent = count > 9 ? '9+' : count;
                        badge.style.display = 'flex';
                    } else {
                        badge.style.display = 'none';
                    }
                }

                function escapeHtml(text) {
                    const div = document.createElement('div');
                    div.textContent = text;
                    return div.innerHTML;
                }
            </script>
        @endif
    @endauth

</body>

</html>

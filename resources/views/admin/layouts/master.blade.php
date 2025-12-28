<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Dashboard') | {{ config('app.name') }}</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

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

    @stack('styles')
</head>

<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="sidebar-brand">
                <div class="logo">
                    <i class="fas fa-store"></i>
                </div>
                <h3>EcommerceBD</h3>
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

                    <li class="has-submenu {{ request()->routeIs('admin.products.*') ? 'open' : '' }}">
                        <a href="#">
                            <i class="fas fa-box"></i>
                            <span class="menu-text">Products</span>
                            <span class="menu-arrow"><i class="fas fa-chevron-right"></i></span>
                        </a>
                        <ul class="submenu {{ request()->routeIs('admin.products.*') ? 'show' : '' }}">
                            <li class="{{ request()->routeIs('admin.products.index') ? 'active' : '' }}">
                                <a href="{{ route('admin.products.index') }}">All Products</a>
                            </li>
                            <li class="{{ request()->routeIs('admin.products.create') ? 'active' : '' }}">
                                <a href="{{ route('admin.products.create') }}">Add New</a>
                            </li>
                            <li><a href="#">Brands</a></li>

                        </ul>
                    </li>

                    <li class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.orders.index') }}">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="menu-text">Orders</span>
                            <span
                                class="badge bg-danger ms-auto">{{ \App\Models\Order::where('order_status', 'pending')->count() }}</span>
                        </a>
                    </li>

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

                    <li class="{{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.coupons.index') }}">
                            <i class="fas fa-ticket-alt"></i>
                            <span class="menu-text">Coupons</span>
                        </a>
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
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <div class="user-info d-none d-md-block">
                                <h6 class="mb-0">{{ auth()->user()->name }}</h6>
                                <small>Administrator</small>
                            </div>
                            <i class="fas fa-chevron-down ms-2"></i>
                        </div>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="{{ route('dashboard') }}" class="dropdown-item">
                                <i class="fas fa-user me-2"></i> My Profile
                            </a>
                            <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                <i class="fas fa-cog me-2"></i> Settings
                            </a>
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
            <div class="admin-content">
                <!-- Breadcrumb -->
                <div class="card border-0 shadow-sm mb-4 rounded-1">
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
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="mb-1">@yield('page-title', 'Dashboard')</h4>
                        <p class="text-muted mb-0">@yield('page-subtitle', 'Welcome back, ' . Auth::user()->name . '!')</p>
                    </div>
                    <div>
                        @yield('page-actions')
                    </div>
                </div>

                <!-- Main Content -->
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- SimpleBar JS -->
    <script src="https://cdn.jsdelivr.net/npm/simplebar@latest/dist/simplebar.min.js"></script>


    <script>
        // DOM Ready
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-apply SimpleBar to common scrollable containers
            document.querySelectorAll('.table-responsive, .table-container').forEach(function(el) {
                if (!el.hasAttribute('data-simplebar')) {
                    el.setAttribute('data-simplebar', '');
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
</body>

</html>

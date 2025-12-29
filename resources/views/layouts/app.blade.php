<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ElectroHub') }}</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --primary-blue: #0d6efd;
            --primary-dark-blue: #0b5ed7;
            --electric-blue: #0096ff;
            --electric-purple: #7b2cbf;
            --neon-green: #00ff88;
            --warning-yellow: #ffc107;
            --dark-bg: #1a1d29;
            --light-bg: #f8f9fa;
        }

        body {
            font-family: 'Roboto', sans-serif;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Poppins', sans-serif;
        }

        /* Header Top Styles */
        .header-top {
            background: linear-gradient(135deg, var(--dark-bg) 0%, #2d3748 100%);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .marquee-container {
            overflow: hidden;
            position: relative;
            background: rgba(0, 0, 0, 0.2);
            border-radius: 6px;
            padding: 5px 10px;
        }

        .marquee-content {
            display: inline-block;
            white-space: nowrap;
            animation: marquee 25s linear infinite;
            padding-left: 100%;
        }

        .marquee-content:hover {
            animation-play-state: paused;
        }

        @keyframes marquee {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-100%);
            }
        }

        .offer-badge {
            background: linear-gradient(90deg, var(--electric-blue), var(--electric-purple));
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-right: 10px;
        }

        /* Language Selector */
        .language-selector {
            position: relative;
        }

        .language-btn {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }

        .language-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: var(--electric-blue);
        }

        .language-flag {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            margin-right: 8px;
            object-fit: cover;
        }

        /* Main Header Styles */
        .main-header {
            background: white;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        }

        .logo {
            font-size: 1.8rem;
            font-weight: 700;
            background: linear-gradient(90deg, var(--electric-blue), var(--electric-purple));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-decoration: none;
        }

        .logo:hover {
            text-decoration: none;
        }

        .logo-icon {
            color: var(--electric-blue);
            font-size: 2rem;
            margin-right: 8px;
        }

        /* Search Bar */
        .search-container {
            position: relative;
            max-width: 500px;
        }

        .search-input {
            border: 2px solid #e9ecef;
            border-radius: 25px;
            padding: 10px 20px 10px 45px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            width: 100%;
        }

        .search-input:focus {
            border-color: var(--electric-blue);
            box-shadow: 0 0 0 0.2rem rgba(0, 150, 255, 0.25);
        }

        .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            z-index: 10;
        }

        /* Header Icons */
        .header-icon {
            position: relative;
            color: #495057;
            font-size: 1.2rem;
            transition: all 0.3s ease;
            padding: 8px;
            border-radius: 50%;
        }

        .header-icon:hover {
            color: var(--electric-blue);
            background-color: rgba(0, 150, 255, 0.1);
            transform: translateY(-2px);
        }

        .cart-count,
        .wishlist-count {
            position: absolute;
            top: -5px;
            right: -5px;
            background: linear-gradient(90deg, #ff416c, #ff4b2b);
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        /* Navigation */
        .main-nav {
            background: linear-gradient(90deg, var(--dark-bg) 0%, #2d3748 100%);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .nav-link-custom {
            color: white !important;
            font-weight: 500;
            padding: 12px 20px !important;
            position: relative;
            transition: all 0.3s ease;
        }

        .nav-link-custom:hover {
            color: var(--neon-green) !important;
            background: rgba(255, 255, 255, 0.05);
        }

        .nav-link-custom.active {
            color: var(--neon-green) !important;
            background: rgba(0, 255, 136, 0.1);
        }

        .nav-link-custom.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 20%;
            width: 60%;
            height: 3px;
            background: var(--neon-green);
            border-radius: 3px;
        }

        /* Dropdown Menu */
        .dropdown-menu-custom {
            background: white;
            border: none;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
            margin-top: 10px;
            min-width: 200px;
        }

        .dropdown-item-custom {
            padding: 10px 20px;
            color: #495057;
            transition: all 0.2s ease;
        }

        .dropdown-item-custom:hover {
            background: linear-gradient(90deg, rgba(0, 150, 255, 0.1), rgba(123, 44, 191, 0.1));
            color: var(--electric-blue);
            padding-left: 25px;
        }

        /* Mobile Menu */
        .mobile-menu-btn {
            background: none;
            border: none;
            width: 40px;
            height: 40px;
            position: relative;
            padding: 0;
        }

        .mobile-menu-btn span {
            display: block;
            width: 24px;
            height: 2px;
            background: var(--dark-bg);
            margin: 5px auto;
            transition: all 0.3s ease;
            position: absolute;
            left: 8px;
        }

        .mobile-menu-btn span:nth-child(1) {
            top: 10px;
        }

        .mobile-menu-btn span:nth-child(2) {
            top: 19px;
        }

        .mobile-menu-btn span:nth-child(3) {
            top: 28px;
        }

        .mobile-menu-btn.active span:nth-child(1) {
            transform: rotate(45deg);
            top: 19px;
        }

        .mobile-menu-btn.active span:nth-child(2) {
            opacity: 0;
        }

        .mobile-menu-btn.active span:nth-child(3) {
            transform: rotate(-45deg);
            top: 19px;
        }

        /* Mobile Menu */
        .mobile-menu {
            background: white;
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            z-index: 1000;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            display: none;
        }

        .mobile-menu.show {
            display: block;
        }

        /* Social Login Buttons */
        .btn-social {
            border-radius: 50px;
            padding: 8px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-social:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-social i {
            font-size: 1.1rem;
        }

        /* Divider */
        .divider {
            position: relative;
            text-align: center;
            border-top: 1px solid #dee2e6;
        }

        .divider span {
            position: relative;
            top: -12px;
            background: white;
            padding: 0 15px;
        }

        /* Form Validation Styles */
        .was-validated .form-control:valid {
            border-color: #28a745;
        }

        .was-validated .form-control:invalid {
            border-color: #dc3545;
        }

        /* Modal Animation */
        .modal.fade .modal-dialog {
            transform: translate(0, -50px);
            transition: transform 0.3s ease-out;
        }

        .modal.show .modal-dialog {
            transform: translate(0, 0);
        }

        /* Modal Content */
        .modal-content {
            border-radius: 15px;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .modal-header {
            border-bottom: 1px solid #dee2e6;
            background: linear-gradient(135deg, var(--electric-blue), var(--electric-purple));
            color: white;
            border-radius: 15px 15px 0 0;
        }

        .modal-header .btn-close {
            filter: invert(1) brightness(100%);
        }

        /* Responsive */
        @media (max-width: 991.98px) {
            .search-container {
                order: 3;
                max-width: 100%;
                margin-top: 15px;
            }

            .nav-link-custom {
                padding: 10px 15px !important;
            }
        }

        @media (max-width: 767.98px) {
            .header-top .d-flex {
                flex-direction: column;
                gap: 10px;
            }

            .marquee-container {
                width: 100%;
            }
        }
    </style>
    <style>
        /* Google Translate Custom Styles */
        #google_translate_element {
            display: none;
        }

        .goog-te-banner-frame {
            display: none !important;
        }

        .goog-te-gadget {
            font-family: 'Roboto', sans-serif !important;
            font-size: 0 !important;
        }

        .goog-te-gadget-simple {
            background-color: transparent !important;
            border: none !important;
            padding: 0 !important;
        }

        .goog-te-menu-value {
            color: #333 !important;
            font-size: 14px !important;
        }

        .goog-te-menu-value span {
            color: #333 !important;
        }

        .goog-te-menu-value:hover {
            text-decoration: none !important;
        }

        .goog-te-gadget img {
            display: none !important;
        }

        .goog-te-combo {
            padding: 8px 12px !important;
            border-radius: 6px !important;
            border: 1px solid #ddd !important;
            font-family: 'Roboto', sans-serif !important;
            font-size: 14px !important;
            background: white !important;
            color: #333 !important;
        }

        .goog-te-combo:focus {
            outline: none !important;
            border-color: var(--electric-blue) !important;
            box-shadow: 0 0 0 2px rgba(0, 150, 255, 0.25) !important;
        }

        /* RTL Support */
        [dir="rtl"] {
            font-family: 'Arial', sans-serif !important;
        }

        [dir="rtl"] .text-start {
            text-align: right !important;
        }

        [dir="rtl"] .text-end {
            text-align: left !important;
        }

        [dir="rtl"] .ms-auto {
            margin-right: auto !important;
            margin-left: 0 !important;
        }

        [dir="rtl"] .me-auto {
            margin-left: auto !important;
            margin-right: 0 !important;
        }

        /* Language Selector Enhancements */
        .language-selector .dropdown-menu {
            max-height: 300px;
            overflow-y: auto;
        }

        .language-flag {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid #dee2e6;
        }

        .language-btn {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            min-width: 100px;
            justify-content: center;
        }

        .language-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: var(--electric-blue);
        }

        .dropdown-item-custom[onclick*="en"] {
            font-weight: bold;
            background: rgba(0, 150, 255, 0.1);
        }
    </style>
    @yield('styles')
</head>

<body>
    <!-- Header Top Section -->
    <div class="header-top py-2">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <!-- Marquee Ads -->
                <div class="marquee-container flex-grow-1 me-3">
                    <div class="marquee-content">
                        @php
                            $ads = [
                                [
                                    'text' => '⚡ Flash Sale: Get 30% OFF on all electronic gadgets!',
                                    'badge' => 'Hot Deal',
                                ],
                                ['text' => '🔋 Free Shipping on orders above $99', 'badge' => 'Free Shipping'],
                                [
                                    'text' => '📱 New Arrival: Latest Smartphones with 2 Years Warranty',
                                    'badge' => 'New',
                                ],
                                [
                                    'text' => '💡 Energy Efficient Appliances - Save up to 40% on electricity',
                                    'badge' => 'Eco-Friendly',
                                ],
                                [
                                    'text' => '🎧 Wireless Earbuds with Noise Cancellation - Limited Stock',
                                    'badge' => 'Trending',
                                ],
                            ];
                        @endphp

                        @foreach ($ads as $ad)
                            <span class="me-4">
                                <span class="offer-badge">{{ $ad['badge'] }}</span>
                                <span class="text-white">{{ $ad['text'] }}</span>
                            </span>
                        @endforeach
                    </div>
                </div>

                <!-- Language Selector -->
                <div class="language-selector">
                    <button class="language-btn d-flex align-items-center" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <img src="https://flagcdn.com/w20/us.png" class="language-flag" alt="English">
                        <span>English</span>
                        <i class="fas fa-chevron-down ms-2" style="font-size: 0.75rem;"></i>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-custom" style="min-width: 150px;">
                        <li>
                            <a class="dropdown-item dropdown-item-custom d-flex align-items-center" href="#"
                                onclick="changeGoogleTranslate('en')">
                                <img src="https://flagcdn.com/w20/us.png" class="language-flag me-2" alt="English">
                                English
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item dropdown-item-custom d-flex align-items-center" href="#"
                                onclick="changeGoogleTranslate('bn')">
                                <img src="https://flagcdn.com/w20/bd.png" class="language-flag me-2" alt="Bangla">
                                বাংলা
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item dropdown-item-custom d-flex align-items-center" href="#"
                                onclick="changeGoogleTranslate('es')">
                                <img src="https://flagcdn.com/w20/es.png" class="language-flag me-2" alt="Spanish">
                                Español
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item dropdown-item-custom d-flex align-items-center" href="#"
                                onclick="changeGoogleTranslate('fr')">
                                <img src="https://flagcdn.com/w20/fr.png" class="language-flag me-2" alt="French">
                                Français
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item dropdown-item-custom d-flex align-items-center" href="#"
                                onclick="changeGoogleTranslate('de')">
                                <img src="https://flagcdn.com/w20/de.png" class="language-flag me-2" alt="German">
                                Deutsch
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item dropdown-item-custom d-flex align-items-center" href="#"
                                onclick="changeGoogleTranslate('zh-CN')">
                                <img src="https://flagcdn.com/w20/cn.png" class="language-flag me-2" alt="Chinese">
                                中文
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item dropdown-item-custom d-flex align-items-center" href="#"
                                onclick="changeGoogleTranslate('hi')">
                                <img src="https://flagcdn.com/w20/in.png" class="language-flag me-2" alt="Hindi">
                                हिन्दी
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item dropdown-item-custom d-flex align-items-center" href="#"
                                onclick="changeGoogleTranslate('ar')">
                                <img src="https://flagcdn.com/w20/sa.png" class="language-flag me-2" alt="Arabic">
                                العربية
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item dropdown-item-custom d-flex align-items-center" href="#"
                                onclick="changeGoogleTranslate('ja')">
                                <img src="https://flagcdn.com/w20/jp.png" class="language-flag me-2" alt="Japanese">
                                日本語
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item dropdown-item-custom d-flex align-items-center" href="#"
                                onclick="changeGoogleTranslate('ko')">
                                <img src="https://flagcdn.com/w20/kr.png" class="language-flag me-2" alt="Korean">
                                한국어
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Google Translate Container - Add this where you want the widget to appear -->
                <div id="google_translate_element" style="display: none;"></div>
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <header class="main-header py-3">
        <div class="container">
            <div class="row align-items-center">
                <!-- Logo -->
                <div class="col-lg-2 col-md-3 col-6">
                    <a href="{{ route('home') }}" class="logo d-flex align-items-center">
                        <i class="fas fa-bolt logo-icon"></i>
                        <span>ElectroHub</span>
                    </a>
                </div>

                <!-- Search Bar -->
                <div class="col-lg-5 col-md-6 order-md-2 order-lg-1 order-3 mt-3 mt-md-0">
                    <div class="search-container">
                        <i class="fas fa-search search-icon"></i>
                        <form action="{{ route('shop') }}" method="GET">
                            <input type="search" name="search" class="search-input"
                                placeholder="Search for electronics, gadgets, appliances..."
                                value="{{ request('search') }}">
                        </form>
                    </div>
                </div>

                <!-- Header Icons -->
                <div class="col-lg-5 col-md-3 col-6 order-md-1 order-lg-2 order-2">
                    <div class="d-flex justify-content-end align-items-center gap-3">
                        <!-- Wishlist -->
                        <a href="{{ route('wishlist.index') }}" class="header-icon position-relative">
                            <i class="far fa-heart"></i>
                            @auth
                                @php
                                    $wishlistCount = auth()->user()->wishlists()->count();
                                @endphp
                                @if ($wishlistCount > 0)
                                    <span class="wishlist-count">{{ $wishlistCount }}</span>
                                @endif
                            @endauth
                        </a>

                        <!-- Cart -->
                        <a href="{{ route('cart.index') }}" class="header-icon position-relative">
                            <i class="fas fa-shopping-cart"></i>
                            @php
                                $cartCount = \App\Models\Cart::count();
                            @endphp
                            @if ($cartCount > 0)
                                <span class="cart-count">{{ $cartCount }}</span>
                            @endif
                        </a>

                        <!-- User Account -->
                        @auth
                            <div class="dropdown">
                                <a href="#" class="d-flex align-items-center text-decoration-none"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="header-icon">
                                        <i class="fas fa-user-circle"></i>
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-custom">
                                    @if (auth()->user()->role === 'admin')
                                        <li>
                                            <a class="dropdown-item dropdown-item-custom"
                                                href="{{ route('admin.dashboard') }}">
                                                <i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard
                                            </a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                    @endif
                                    <li>
                                        <a class="dropdown-item dropdown-item-custom" href="{{ route('dashboard') }}">
                                            <i class="fas fa-user me-2"></i>My Account
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item dropdown-item-custom" href="{{ route('orders.index') }}">
                                            <i class="fas fa-box me-2"></i>My Orders
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item dropdown-item-custom"
                                            href="{{ route('wishlist.index') }}">
                                            <i class="fas fa-heart me-2"></i>Wishlist
                                        </a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item dropdown-item-custom">
                                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @else
                            <!-- Login and Register Buttons with Modals -->
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                    data-bs-target="#loginModal">
                                    <i class="fas fa-sign-in-alt me-1"></i> Login
                                </button>
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#registerModal">
                                    <i class="fas fa-user-plus me-1"></i> Register
                                </button>
                            </div>
                        @endauth

                        <!-- Mobile Menu Toggle -->
                        <button class="mobile-menu-btn d-lg-none ms-2" id="mobileMenuToggle">
                            <span></span>
                            <span></span>
                            <span></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Navigation -->
    <nav class="main-nav d-none d-lg-block">
        <div class="container">
            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link nav-link-custom {{ request()->routeIs('home') ? 'active' : '' }}"
                        href="{{ route('home') }}">
                        <i class="fas fa-home me-2"></i> Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-custom {{ request()->routeIs('shop*') ? 'active' : '' }}"
                        href="{{ route('shop') }}">
                        <i class="fas fa-store me-2"></i> Shop
                    </a>
                </li>

                <!-- Categories Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link nav-link-custom dropdown-toggle {{ request()->routeIs('category.*') ? 'active' : '' }}"
                        href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-th-large me-2"></i> Categories
                    </a>
                    <ul class="dropdown-menu dropdown-menu-custom">
                        @php
                            $categories = \App\Models\Category::whereNull('parent_id')
                                ->where('is_active', true)
                                ->with('children')
                                ->limit(8)
                                ->get();
                        @endphp

                        @foreach ($categories as $category)
                            <li>
                                <a class="dropdown-item dropdown-item-custom"
                                    href="{{ route('category.show', $category->slug) }}">
                                    <i class="fas fa-folder me-2"></i> {{ $category->name }}
                                </a>
                            </li>
                            @if ($category->children->isNotEmpty())
                                @foreach ($category->children as $child)
                                    <li>
                                        <a class="dropdown-item dropdown-item-custom ps-4"
                                            href="{{ route('category.show', $child->slug) }}">
                                            <i class="fas fa-angle-right me-2"></i> {{ $child->name }}
                                        </a>
                                    </li>
                                @endforeach
                            @endif
                            @if (!$loop->last)
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                            @endif
                        @endforeach

                        <li>
                            <a class="dropdown-item dropdown-item-custom text-primary" href="#">
                                <i class="fas fa-eye me-2"></i> View All Categories
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link nav-link-custom {{ request()->routeIs('about') ? 'active' : '' }}"
                        href="{{ route('about') }}">
                        <i class="fas fa-info-circle me-2"></i> About Us
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-custom {{ request()->routeIs('services') ? 'active' : '' }}"
                        href="{{ route('services') }}">
                        <i class="fas fa-cogs me-2"></i> Services
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-custom {{ request()->routeIs('contact') ? 'active' : '' }}"
                        href="{{ route('contact') }}">
                        <i class="fas fa-phone-alt me-2"></i> Contact
                    </a>
                </li>

                <!-- Special Offers -->
                <li class="nav-item ms-auto">
                    <a class="nav-link nav-link-custom text-warning" href="{{ route('offers') }}">
                        <i class="fas fa-gift me-2"></i> Special Offers
                        <span class="badge bg-danger ms-1">Hot</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Mobile Menu -->
    <div class="mobile-menu d-lg-none" id="mobileMenu">
        <div class="container py-3">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link py-2 {{ request()->routeIs('home') ? 'active' : '' }}"
                        href="{{ route('home') }}">
                        <i class="fas fa-home me-3"></i> Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-2 {{ request()->routeIs('shop*') ? 'active' : '' }}"
                        href="{{ route('shop') }}">
                        <i class="fas fa-store me-3"></i> Shop
                    </a>
                </li>

                <!-- Mobile Categories -->
                <li class="nav-item">
                    <a class="nav-link py-2 {{ request()->routeIs('category.*') ? 'active' : '' }}" href="#">
                        <i class="fas fa-th-large me-3"></i> Categories
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link py-2 {{ request()->routeIs('about') ? 'active' : '' }}"
                        href="{{ route('about') }}">
                        <i class="fas fa-info-circle me-3"></i> About Us
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-2 {{ request()->routeIs('services') ? 'active' : '' }}"
                        href="{{ route('services') }}">
                        <i class="fas fa-cogs me-3"></i> Services
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-2 {{ request()->routeIs('contact') ? 'active' : '' }}"
                        href="{{ route('contact') }}">
                        <i class="fas fa-phone-alt me-3"></i> Contact
                    </a>
                </li>

                <!-- Mobile User Menu -->
                <li class="nav-item border-top mt-2 pt-2">
                    @auth
                        <div class="nav-link py-2">
                            <i class="fas fa-user me-3"></i> {{ auth()->user()->name }}
                        </div>
                        <ul class="nav flex-column ps-4">
                            <li class="nav-item">
                                <a class="nav-link py-1" href="{{ route('dashboard') }}">
                                    <i class="fas fa-user me-2"></i> My Account
                                </a>
                            </li>
                            @if (auth()->user()->role === 'admin')
                                <li class="nav-item">
                                    <a class="nav-link py-1" href="{{ route('admin.dashboard') }}">
                                        <i class="fas fa-tachometer-alt me-2"></i> Admin
                                    </a>
                                </li>
                            @endif
                            <li class="nav-item">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="nav-link py-1 border bg-transparent text-start w-100">
                                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    @else
                        <div class="d-flex gap-2 px-3">
                            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm flex-grow-1">
                                <i class="fas fa-sign-in-alt me-1"></i> Login
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-primary btn-sm flex-grow-1">
                                <i class="fas fa-user-plus me-1"></i> Register
                            </a>
                        </div>
                    @endauth
                </li>
            </ul>
        </div>
    </div>

    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Login to ElectroHub</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Social Login Buttons -->
                    <div class="text-center mb-4">
                        <p class="text-muted mb-3">Login with social account</p>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="{{ route('login.google') }}" class="btn btn-outline-danger btn-social">
                                <i class="fab fa-google me-2"></i> Google
                            </a>
                            <a href="{{ route('login.facebook') }}" class="btn btn-outline-primary btn-social">
                                <i class="fab fa-facebook me-2"></i> Facebook
                            </a>
                        </div>
                        <div class="divider my-4">
                            <span class="px-3 bg-white text-muted">or login with email</span>
                        </div>
                    </div>

                    <!-- Login Form -->
                    <form method="POST" action="{{ route('login') }}" id="loginForm">
                        @csrf

                        <div class="mb-3">
                            <label for="login_email" class="form-label">Email Address *</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                id="login_email" name="email" value="{{ old('email') }}" required autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="login_password" class="form-label">Password *</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="login_password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>

                        <div class="text-center mt-3">
                            <a href="{{ route('password.request') }}" class="text-decoration-none">
                                Forgot your password?
                            </a>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-center">
                    <p class="mb-0">Don't have an account?
                        <a href="#" class="text-primary" data-bs-toggle="modal"
                            data-bs-target="#registerModal" data-bs-dismiss="modal">Register here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Register Modal -->
    <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registerModalLabel">Create Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Social Register Buttons -->
                    <div class="text-center mb-4">
                        <p class="text-muted mb-3">Register with social account</p>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="{{ route('login.google') }}" class="btn btn-outline-danger btn-social">
                                <i class="fab fa-google me-2"></i> Google
                            </a>
                            <a href="{{ route('login.facebook') }}" class="btn btn-outline-primary btn-social">
                                <i class="fab fa-facebook me-2"></i> Facebook
                            </a>
                        </div>
                        <div class="divider my-4">
                            <span class="px-3 bg-white text-muted">or register with email</span>
                        </div>
                    </div>

                    <!-- Registration Form -->
                    <form method="POST" action="{{ route('register') }}" id="registerForm">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                    id="phone" name="phone" value="{{ old('phone') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address *</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password *</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password *</label>
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                            <label class="form-check-label" for="terms">
                                I agree to the <a href="{{ route('terms') }}" target="_blank"
                                    class="text-decoration-none">Terms & Conditions</a>
                            </label>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Create Account</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-center">
                    <p class="mb-0">Already have an account?
                        <a href="#" class="text-primary" data-bs-toggle="modal" data-bs-target="#loginModal"
                            data-bs-dismiss="modal">Login here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
    <script>
        // Global auth enforcement across all pages
        (function() {
            const IS_AUTH = {{ auth()->check() ? 'true' : 'false' }};
            const wishlistUrl = '{{ route('wishlist.index') }}';
            const cartUrl = '{{ route('cart.index') }}';

            function requireLoginPrompt() {
                const loginModalEl = document.getElementById('loginModal');
                if (loginModalEl) {
                    const loginModal = new bootstrap.Modal(loginModalEl);
                    loginModal.show();
                }
                if (window.Toast) {
                    window.Toast.fire({
                        icon: 'warning',
                        title: 'Please login to continue'
                    });
                }
            }

            // Capture-phase guard to block before page-specific handlers
            document.addEventListener('click', function(e) {
                if (IS_AUTH) return; // no-op if logged in

                // Guard header wishlist/cart links
                const headerLink = e.target.closest('a.header-icon');
                if (headerLink) {
                    const href = headerLink.getAttribute('href') || '';
                    if (href === wishlistUrl || href === cartUrl) {
                        e.preventDefault();
                        e.stopPropagation();
                        requireLoginPrompt();
                        return;
                    }
                }

                // Guard product actions
                if (e.target.closest('.add-to-cart-btn') || e.target.closest('.wishlist-btn')) {
                    e.preventDefault();
                    e.stopPropagation();
                    requireLoginPrompt();
                    return;
                }
            }, true);
        })();
    </script>
    <script>
        // Handle add to cart with login check
        // Handle add to cart with login check
        // Add to cart function (use this in all your blade files)
        function addToCart(productId, quantity = 1) {
            fetch('{{ route('cart.add') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: quantity
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Toast.fire({
                            icon: 'success',
                            title: data.message
                        });
                        updateCartCount(data.cart_count);
                    } else if (data.requires_login) {
                        const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
                        loginModal.show();
                        Toast.fire({
                            icon: 'warning',
                            title: 'Please login to add items to cart'
                        });
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: data.message || 'Failed to add to cart'
                        });
                    }
                })
                .catch(error => {
                    console.error('Add to cart error:', error);
                    Toast.fire({
                        icon: 'error',
                        title: 'Network error. Please try again.'
                    });
                });
        }

        // Update cart count in header
        function updateCartCount(count) {
            const cartCountElement = document.querySelector('.cart-count');
            if (cartCountElement) {
                cartCountElement.textContent = count;
                cartCountElement.style.display = count > 0 ? 'flex' : 'none';
            }
        }

        // Initialize cart count on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Fetch cart count if user is logged in
            @if (auth()->check() && auth()->user()->role === 'customer')
                updateCartCount({{ auth()->user()->cart()->count() }});
            @endif
        });
    </script>
    <script>
        // Google Translate functions
        let googleTranslateInitialized = false;
        let currentLanguage = 'en'; // Default to English

        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'en', // Default page language to English
                includedLanguages: 'en,bn,es,fr,de,zh-CN,hi,ar,ja,ko', // Put English first
                layout: google.translate.TranslateElement.InlineLayout.HORIZONTAL,
                autoDisplay: false,
                multilanguagePage: true,
                gaTrack: true,
                gaId: 'UA-XXXXXXXXX-X' // Add your Google Analytics ID if needed
            }, 'google_translate_element');

            googleTranslateInitialized = true;
            // Hide the Google Translate toolbar
            hideGoogleTranslateToolbar();
        }

        // Load Google Translate script
        function loadGoogleTranslate() {
            if (!window.googleTranslateElementInit) {
                var script = document.createElement('script');
                script.type = 'text/javascript';
                script.src = 'https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit';
                document.head.appendChild(script);
            } else {
                // If already loaded, just initialize
                googleTranslateElementInit();
            }
        }

        // Change language using Google Translate
        function changeGoogleTranslate(lang) {
            // Show loading indicator
            Toast.fire({
                icon: 'info',
                title: 'Changing language...'
            });

            // Load Google Translate if not already loaded
            if (!googleTranslateInitialized) {
                loadGoogleTranslate();

                // Wait for Google Translate to load
                const checkInterval = setInterval(() => {
                    if (googleTranslateInitialized) {
                        clearInterval(checkInterval);
                        performLanguageChange(lang);
                    }
                }, 100);
            } else {
                performLanguageChange(lang);
            }
        }

        // Perform the actual language change
        function performLanguageChange(lang) {
            try {
                const select = document.querySelector('.goog-te-combo');
                if (select) {
                    select.value = lang;
                    select.dispatchEvent(new Event('change'));

                    // Update UI
                    updateLanguageButton(lang);

                    // Save preference to localStorage
                    localStorage.setItem('googleTranslateLanguage', lang);
                    currentLanguage = lang;

                    // Show success message
                    setTimeout(() => {
                        Toast.fire({
                            icon: 'success',
                            title: getLanguageName(lang) + ' selected'
                        });
                    }, 1000);
                }
            } catch (error) {
                console.error('Language change failed:', error);
                Toast.fire({
                    icon: 'error',
                    title: 'Language change failed'
                });
            }
        }

        // Update language button text and flag
        function updateLanguageButton(lang) {
            const languageData = {
                'en': { // Put English first
                    flag: 'https://flagcdn.com/w20/us.png',
                    name: 'English'
                },
                'bn': {
                    flag: 'https://flagcdn.com/w20/bd.png',
                    name: 'বাংলা'
                },
                'es': {
                    flag: 'https://flagcdn.com/w20/es.png',
                    name: 'Español'
                },
                'fr': {
                    flag: 'https://flagcdn.com/w20/fr.png',
                    name: 'Français'
                },
                'de': {
                    flag: 'https://flagcdn.com/w20/de.png',
                    name: 'Deutsch'
                },
                'zh-CN': {
                    flag: 'https://flagcdn.com/w20/cn.png',
                    name: '中文'
                },
                'hi': {
                    flag: 'https://flagcdn.com/w20/in.png',
                    name: 'हिन्दी'
                },
                'ar': {
                    flag: 'https://flagcdn.com/w20/sa.png',
                    name: 'العربية'
                },
                'ja': {
                    flag: 'https://flagcdn.com/w20/jp.png',
                    name: '日本語'
                },
                'ko': {
                    flag: 'https://flagcdn.com/w20/kr.png',
                    name: '한국어'
                }
            };

            const langData = languageData[lang] || languageData['en']; // Default to English
            const button = document.querySelector('.language-btn');

            if (button) {
                const img = button.querySelector('img');
                const span = button.querySelector('span');

                if (img) img.src = langData.flag;
                if (span) span.textContent = langData.name;
            }
        }

        // Get language name
        function getLanguageName(lang) {
            const languages = {
                'en': 'English', // Put English first
                'bn': 'Bangla',
                'es': 'Spanish',
                'fr': 'French',
                'de': 'German',
                'zh-CN': 'Chinese',
                'hi': 'Hindi',
                'ar': 'Arabic',
                'ja': 'Japanese',
                'ko': 'Korean'
            };
            return languages[lang] || 'English'; // Default to English
        }

        // Hide Google Translate toolbar
        function hideGoogleTranslateToolbar() {
            // Hide the default Google Translate toolbar
            const style = document.createElement('style');
            style.innerHTML = `
            .goog-te-banner-frame {
                display: none !important;
            }
            .goog-te-menu-value span {
                color: #333 !important;
            }
            .goog-te-menu-value {
                color: #333 !important;
            }
            .goog-te-gadget {
                font-family: 'Roboto', sans-serif !important;
            }
            .goog-te-combo {
                padding: 5px !important;
                border-radius: 5px !important;
                border: 1px solid #ddd !important;
            }
        `;
            document.head.appendChild(style);

            // Remove the "Powered by Google" text
            setTimeout(() => {
                const poweredBy = document.querySelector('.goog-logo-link');
                if (poweredBy) poweredBy.style.display = 'none';

                const text = document.querySelector('.goog-te-gadget');
                if (text) {
                    const child = text.querySelector('span');
                    if (child) child.style.display = 'none';
                }
            }, 500);
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Check for saved language preference
            const savedLang = localStorage.getItem('googleTranslateLanguage') || 'en'; // Default to English
            currentLanguage = savedLang;

            // Update button to show saved language
            updateLanguageButton(savedLang);

            // Initialize Google Translate on first click
            document.querySelector('.language-btn').addEventListener('click', function(e) {
                // Only load Google Translate if not already initialized
                if (!googleTranslateInitialized) {
                    // Prevent dropdown from opening immediately
                    e.stopPropagation();

                    // Show loading message
                    Toast.fire({
                        icon: 'info',
                        title: 'Loading...'
                    });

                    loadGoogleTranslate();

                    // Wait a bit then open the dropdown
                    setTimeout(() => {
                        const dropdown = new bootstrap.Dropdown(this);
                        dropdown.show();
                    }, 500);
                }
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.language-selector')) {
                    const dropdown = bootstrap.Dropdown.getInstance(document.querySelector(
                        '.language-btn'));
                    if (dropdown) dropdown.hide();
                }
            });

            // Add RTL support for Arabic
            function updatePageDirection(lang) {
                if (lang === 'ar') {
                    document.documentElement.dir = 'rtl';
                    document.documentElement.lang = 'ar';

                    // Add RTL styles
                    const rtlStyle = document.createElement('style');
                    rtlStyle.innerHTML = `
                    [dir="rtl"] .text-start { text-align: right !important; }
                    [dir="rtl"] .text-end { text-align: left !important; }
                    [dir="rtl"] .ms-1 { margin-left: 0 !important; margin-right: 0.25rem !important; }
                    [dir="rtl"] .ms-2 { margin-left: 0 !important; margin-right: 0.5rem !important; }
                    [dir="rtl"] .me-1 { margin-right: 0 !important; margin-left: 0.25rem !important; }
                    [dir="rtl"] .me-2 { margin-right: 0 !important; margin-left: 0.5rem !important; }
                    [dir="rtl"] .ps-1 { padding-left: 0 !important; padding-right: 0.25rem !important; }
                    [dir="rtl"] .pe-1 { padding-right: 0 !important; padding-left: 0.25rem !important; }
                `;
                    document.head.appendChild(rtlStyle);
                } else {
                    document.documentElement.dir = 'ltr';
                    document.documentElement.lang = lang;
                }
            }

            // Initialize page direction
            updatePageDirection(savedLang);

            // Listen for Google Translate language changes
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'attributes' && mutation.attributeName === 'lang') {
                        const newLang = document.documentElement.lang;
                        if (newLang !== currentLanguage) {
                            currentLanguage = newLang;
                            updateLanguageButton(newLang);
                            updatePageDirection(newLang);
                            localStorage.setItem('googleTranslateLanguage', newLang);
                        }
                    }
                });
            });

            observer.observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['lang']
            });
        });

        // Handle Google Translate callback errors
        window.googleTranslateError = function(error) {
            console.error('Google Translate error:', error);
            Toast.fire({
                icon: 'error',
                title: 'Google Translate failed to load'
            });
        };

        // Alternative method for language change (if Google Translate widget fails)
        function translatePage(lang) {
            // Get all text nodes and translate them
            // This is a fallback method - not as good as Google Translate
            const textElements = document.querySelectorAll(
                'p, span, h1, h2, h3, h4, h5, h6, a, button, li, td, th, label, input[placeholder]');

            // In a real implementation, you would have translation data
            // For now, just show a message
            Toast.fire({
                icon: 'info',
                title: 'Language changing...'
            });

            // Save the language preference
            localStorage.setItem('preferredLanguage', lang);
            updateLanguageButton(lang);
        }
    </script>
    <script>
        // Mobile Menu Toggle
        document.getElementById('mobileMenuToggle').addEventListener('click', function() {
            this.classList.toggle('active');
            document.getElementById('mobileMenu').classList.toggle('show');
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const mobileMenu = document.getElementById('mobileMenu');
            const mobileToggle = document.getElementById('mobileMenuToggle');

            if (!mobileMenu.contains(event.target) && !mobileToggle.contains(event.target)) {
                mobileMenu.classList.remove('show');
                mobileToggle.classList.remove('active');
            }
        });

        // Update cart and wishlist counts (simulated)
        function updateCartCount(count) {
            const cartCount = document.querySelector('.cart-count');
            if (cartCount) {
                cartCount.textContent = count;
                cartCount.style.display = count > 0 ? 'flex' : 'none';
            }
        }

        function updateWishlistCount(count) {
            const wishlistCount = document.querySelector('.wishlist-count');
            if (wishlistCount) {
                wishlistCount.textContent = count;
                wishlistCount.style.display = count > 0 ? 'flex' : 'none';
            }
        }

        // Marquee pause on hover
        const marqueeContent = document.querySelector('.marquee-content');
        if (marqueeContent) {
            marqueeContent.addEventListener('mouseenter', function() {
                this.style.animationPlayState = 'paused';
            });

            marqueeContent.addEventListener('mouseleave', function() {
                this.style.animationPlayState = 'running';
            });
        }
    </script>

    <script>
        // Handle modal switching between login and register
        document.addEventListener('DOMContentLoaded', function() {
            // Switch from login to register
            document.querySelectorAll('[data-bs-target="#registerModal"]').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const loginModal = bootstrap.Modal.getInstance(document.getElementById(
                        'loginModal'));
                    const registerModal = new bootstrap.Modal(document.getElementById(
                        'registerModal'));

                    if (loginModal) {
                        loginModal.hide();
                        loginModal._element.addEventListener('hidden.bs.modal', function() {
                            registerModal.show();
                        }, {
                            once: true
                        });
                    } else {
                        registerModal.show();
                    }
                });
            });

            // Switch from register to login
            document.querySelectorAll('[data-bs-target="#loginModal"]').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const registerModal = bootstrap.Modal.getInstance(document.getElementById(
                        'registerModal'));
                    const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));

                    if (registerModal) {
                        registerModal.hide();
                        registerModal._element.addEventListener('hidden.bs.modal', function() {
                            loginModal.show();
                        }, {
                            once: true
                        });
                    } else {
                        loginModal.show();
                    }
                });
            });

            // Form Validation
            const loginForm = document.getElementById('loginForm');
            const registerForm = document.getElementById('registerForm');

            if (loginForm) {
                loginForm.addEventListener('submit', function(e) {
                    if (!this.checkValidity()) {
                        e.preventDefault();
                        e.stopPropagation();
                    }
                    this.classList.add('was-validated');
                });
            }

            if (registerForm) {
                registerForm.addEventListener('submit', function(e) {
                    const password = document.getElementById('password').value;
                    const confirmPassword = document.getElementById('password_confirmation').value;
                    const terms = document.getElementById('terms').checked;

                    if (!terms) {
                        e.preventDefault();
                        Toast.fire({
                            icon: 'warning',
                            title: 'Please accept the terms and conditions'
                        });
                        return;
                    }

                    if (password !== confirmPassword) {
                        e.preventDefault();
                        Toast.fire({
                            icon: 'error',
                            title: 'Passwords do not match'
                        });
                        return;
                    }

                    if (!this.checkValidity()) {
                        e.preventDefault();
                        e.stopPropagation();
                    }
                    this.classList.add('was-validated');
                });
            }

            // Show modal on authentication errors
            @if ($errors->has('email') || $errors->has('password'))
                const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
                loginModal.show();
            @endif

            @if ($errors->has('name') || $errors->has('email') || $errors->has('password'))
                const registerModal = new bootstrap.Modal(document.getElementById('registerModal'));
                registerModal.show();
            @endif
        });
    </script>
    <!-- Initialize SweetAlert -->
    <script>
        window.addEventListener('load', function() {
            // Global SweetAlert configuration
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            // Make Toast globally available
            window.Toast = Toast;
        });
    </script>

    <!-- Flash Messages -->
    @if (session('success'))
        <script>
            Toast.fire({
                icon: 'success',
                title: '{{ session('success') }}'
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Toast.fire({
                icon: 'error',
                title: '{{ session('error') }}'
            });
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const timer = document.querySelector('[data-deal-end]');
            if (!timer) return;

            const endTimestamp = parseInt(timer.dataset.dealEnd) * 1000;

            function updateTimer() {
                const diff = endTimestamp - Date.now();

                if (diff <= 0) {
                    timer.querySelector('.hours').innerText = '00';
                    timer.querySelector('.minutes').innerText = '00';
                    timer.querySelector('.seconds').innerText = '00';
                    return;
                }

                timer.querySelector('.hours').innerText =
                    Math.floor(diff / 3600000);

                timer.querySelector('.minutes').innerText =
                    Math.floor((diff / 60000) % 60);

                timer.querySelector('.seconds').innerText =
                    Math.floor((diff / 1000) % 60);
            }

            updateTimer();
            setInterval(updateTimer, 1000);
        });
    </script>
    <script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

</body>

</html>

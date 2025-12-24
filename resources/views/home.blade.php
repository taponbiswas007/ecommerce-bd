@extends('layouts.app')

@section('title', 'Mega Home - ElectroHub')

@section('styles')


    <style>
        /* Custom Styles for Mega Homepage */
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6f42c1;
            --accent-color: #20c997;
            --dark-color: #212529;
            --light-color: #f8f9fa;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, var(--dark-color) 0%, #343a40 100%);
            position: relative;
            overflow: hidden;
        }

        .hero-gradient {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        }

        .floating-element {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        /* Mega Slider */
        .mega-slider {
            height: 500px;
            border-radius: 20px;
            overflow: hidden;
        }

        .mega-slider img {
            height: 100%;
            object-fit: cover;
        }

        .slider-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.8), transparent);
            padding: 40px;
            color: white;
        }

        /* Category Cards */
        .category-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
        }

        .category-card {
            background: white;
            border-radius: 15px;
            padding: 25px 15px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .category-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
            border-color: var(--primary-color);
        }

        .category-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            color: white;
            font-size: 24px;
        }

        /* Product Cards */
        .product-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            background: white;
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        .product-img {
            height: 200px;
            object-fit: cover;
            width: 100%;
        }

        .discount-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: var(--danger-color);
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .featured-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: var(--warning-color);
            color: var(--dark-color);
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .price {
            font-size: 1.2rem;
            font-weight: bold;
            color: var(--primary-color);
        }

        .old-price {
            text-decoration: line-through;
            color: #6c757d;
            font-size: 0.9rem;
        }

        /* Flash Sale Timer */
        .flash-sale-timer {
            background: linear-gradient(135deg, #ff416c, #ff4b2b);
            border-radius: 15px;
            padding: 20px;
            color: white;
        }

        .timer-box {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            padding: 10px;
            text-align: center;
            min-width: 60px;
        }

        .timer-number {
            font-size: 2rem;
            font-weight: bold;
            line-height: 1;
        }

        .timer-label {
            font-size: 0.8rem;
            opacity: 0.9;
        }

        /* Features Section */
        .feature-box {
            text-align: center;
            padding: 30px;
            border-radius: 15px;
            background: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .feature-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 30px;
        }

        /* Deal of the Day */
        .deal-of-the-day {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            overflow: hidden;
            color: white;
        }

        .deal-timer {
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            padding: 15px;
        }

        /* Brands Section */
        .brand-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .brand-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .brand-img {
            height: 50px;
            object-fit: contain;
            filter: grayscale(100%);
            transition: filter 0.3s ease;
        }

        .brand-card:hover .brand-img {
            filter: grayscale(0%);
        }

        /* Newsletter */
        .newsletter-section {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 20px;
            padding: 50px;
            color: white;
        }

        .newsletter-input {
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.2);
            color: white;
            border-radius: 50px;
            padding: 15px 25px;
        }

        .newsletter-input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .newsletter-input:focus {
            background: rgba(255, 255, 255, 0.2);
            border-color: white;
            box-shadow: none;
            color: white;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .mega-slider {
                height: 300px;
            }

            .category-grid {
                grid-template-columns: repeat(3, 1fr);
            }

            .hero-section h1 {
                font-size: 2rem;
            }

            .timer-box {
                min-width: 45px;
            }

            .timer-number {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 576px) {
            .category-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .product-img {
                height: 150px;
            }
        }

        /* Animation Classes */
        .animate-on-scroll {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease;
        }

        .animate-on-scroll.animated {
            opacity: 1;
            transform: translateY(0);
        }

        /* Quick View Modal */
        .quick-view-modal .modal-dialog {
            max-width: 900px;
        }

        /* Rating Stars */
        .rating-stars {
            color: var(--warning-color);
        }

        .rating-count {
            color: #6c757d;
            font-size: 0.9rem;
        }
    </style>
@endsection

@section('content')
    <!-- Hero Section -->
    <section class="hero-section py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <div class="text-white">
                        <span class="badge bg-danger mb-3 px-3 py-2 rounded-pill">🔥 Limited Time Offer</span>
                        <h1 class="display-4 fw-bold mb-4">Get Up to <span class="text-warning">50% OFF</span> on Electronics
                        </h1>
                        <p class="lead mb-4">Discover the latest gadgets and electronics at unbeatable prices. Shop now and
                            experience premium quality with amazing discounts!</p>
                        <div class="d-flex flex-wrap gap-3">
                            <a href="#featured-products" class="btn btn-light btn-lg px-4 py-3">
                                <i class="fas fa-shopping-cart me-2"></i> Shop Now
                            </a>
                            <a href="#flash-sale" class="btn btn-outline-light btn-lg px-4 py-3">
                                <i class="fas fa-bolt me-2"></i> Flash Sale
                            </a>
                        </div>
                        <div class="mt-5">
                            <div class="d-flex align-items-center gap-4">
                                <div class="text-center">
                                    <h3 class="fw-bold mb-0">10K+</h3>
                                    <p class="mb-0 opacity-75">Happy Customers</p>
                                </div>
                                <div class="text-center">
                                    <h3 class="fw-bold mb-0">5K+</h3>
                                    <p class="mb-0 opacity-75">Products</p>
                                </div>
                                <div class="text-center">
                                    <h3 class="fw-bold mb-0">24/7</h3>
                                    <p class="mb-0 opacity-75">Support</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="position-relative">
                        <!-- Swiper Slider -->
                        <div class="swiper mega-slider">
                            <div class="swiper-wrapper">
                                @for ($i = 1; $i <= 5; $i++)
                                    <div class="swiper-slide position-relative">
                                        <img src="https://images.unsplash.com/photo-1550009158-9ebf69173e03?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80"
                                            alt="Slider {{ $i }}" class="img-fluid">
                                        <div class="slider-overlay">
                                            <h3 class="fw-bold">Smart Home Collection</h3>
                                            <p>Transform your home with our latest smart devices</p>
                                        </div>
                                    </div>
                                @endfor
                            </div>
                            <div class="swiper-pagination"></div>
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Flash Sale Timer -->
    <section id="flash-sale" class="py-5">
        <div class="container">
            <div class="flash-sale-timer">
                <div class="row align-items-center">
                    <div class="col-lg-3 mb-4 mb-lg-0">
                        <h2 class="fw-bold mb-0"><i class="fas fa-bolt me-2"></i> FLASH SALE</h2>
                        <p class="mb-0 opacity-75">Ends in:</p>
                    </div>
                    <div class="col-lg-9">
                        <div class="d-flex justify-content-center justify-content-lg-end gap-3">
                            <div class="timer-box">
                                <div class="timer-number" id="days">00</div>
                                <div class="timer-label">DAYS</div>
                            </div>
                            <div class="timer-box">
                                <div class="timer-number" id="hours">00</div>
                                <div class="timer-label">HOURS</div>
                            </div>
                            <div class="timer-box">
                                <div class="timer-number" id="minutes">00</div>
                                <div class="timer-label">MINUTES</div>
                            </div>
                            <div class="timer-box">
                                <div class="timer-number" id="seconds">00</div>
                                <div class="timer-label">SECONDS</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12">
                    <h2 class="fw-bold mb-3">Shop by Categories</h2>
                    <p class="text-muted">Browse products by categories</p>
                </div>
            </div>
            <div class="category-grid">

                @foreach ($categories as $category)
                    <a href="{{ route('category.show', strtolower(str_replace(' ', '-', $category['name']))) }}"
                        class="text-decoration-none">
                        <div class="category-card">
                            <div class="category-icon">
                                @if ($category->image)
                                    <img class=" img-fluid" src="{{ asset('storage/' . $category->image) }}"
                                        alt="{{ $category->name }}" class="category-image">
                                @endif
                            </div>
                            <h5 class="fw-bold mb-2">{{ $category->name }}</h5>
                            <p class="text-muted mb-0">{{ $category->description }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section id="featured-products" class="py-5">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="fw-bold mb-2">Featured Products</h2>
                            <p class="text-muted">Most popular items this week</p>
                        </div>
                        <a href="{{ route('shop') }}" class="btn btn-outline-primary">
                            View All <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="row">
                @php
                    $featuredProducts = \App\Models\Product::with(['primaryImage'])
                        ->where('is_featured', 1)
                        ->where('is_active', 1)
                        ->where('stock_quantity', '>', 0)
                        ->latest()
                        ->limit(8)
                        ->get();
                @endphp


                @foreach ($featuredProducts as $product)
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="product-card h-100">
                            <div class="position-relative">
                                @php
                                    $image = $product->primaryImage ?? $product->images->first();
                                @endphp

                                <img src="{{ $image ? asset('storage/' . $image->image_path) : 'https://via.placeholder.com/300x200' }}"
                                    alt="{{ $image->alt_text ?? $product->name }}" class="product-img">

                                @if ($product->has_discount)
                                    <span class="discount-badge">-{{ $product->discount_percentage }}%</span>
                                @endif
                                <span class="featured-badge">Featured</span>
                                <div class="position-absolute bottom-0 start-0 end-0 p-3 bg-dark bg-opacity-50">
                                    <button class="btn btn-sm btn-light w-100 quick-view-btn"
                                        data-product-id="{{ $product->id }}">
                                        <i class="fas fa-eye me-2"></i> Quick View
                                    </button>
                                </div>
                            </div>
                            <div class="p-3">
                                <h5 class="fw-bold mb-2">
                                    <a href="{{ route('product.show', $product->slug) }}"
                                        class="text-decoration-none text-dark">
                                        {{ Str::limit($product->name, 40) }}
                                    </a>
                                </h5>
                                <div class="d-flex align-items-center mb-2">
                                    <div class="rating-stars">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $product->average_rating)
                                                <i class="fas fa-star"></i>
                                            @else
                                                <i class="far fa-star"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="rating-count ms-2">({{ $product->total_reviews }})</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        @if ($product->has_discount)
                                            <span class="price">৳{{ number_format($product->discount_price, 2) }}</span>
                                            <span
                                                class="old-price ms-2">৳{{ number_format($product->base_price, 2) }}</span>
                                        @else
                                            <span class="price">৳{{ number_format($product->base_price, 2) }}</span>
                                        @endif
                                    </div>
                                    <button class="btn btn-primary btn-sm add-to-cart"
                                        data-product-id="{{ $product->id }}">
                                        <i class="fas fa-cart-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Deal of the Day -->
    @if ($dealProduct)
        <section class="py-5">
            <div class="container">
                <div class="deal-of-the-day">
                    <div class="row align-items-center">
                        <div class="col-lg-6 p-5 text-white">
                            <span class="badge bg-warning text-dark mb-3 px-3 py-2">
                                Deal of the Day
                            </span>

                            <h2 class="fw-bold mb-3">{{ $dealProduct->name }}</h2>
                            <p class="mb-4">
                                {{ Str::limit(strip_tags($dealProduct->short_description), 120) }}
                            </p>

                            {{-- TIMER --}}
                            <div class="deal-timer mb-4"
                                data-deal-end="{{ optional($dealProduct->deal_end_at)->timestamp }}">

                                <p class="mb-2">Hurry up! Offer ends in:</p>
                                <div class="d-flex gap-2">
                                    <div class="text-center">
                                        <div class="bg-dark px-3 py-2 rounded">
                                            <h4 class="hours">00</h4>
                                        </div>
                                        <small>Hours</small>
                                    </div>
                                    <div class="text-center">
                                        <div class="bg-dark px-3 py-2 rounded">
                                            <h4 class="minutes">00</h4>
                                        </div>
                                        <small>Minutes</small>
                                    </div>
                                    <div class="text-center">
                                        <div class="bg-dark px-3 py-2 rounded">
                                            <h4 class="seconds">00</h4>
                                        </div>
                                        <small>Seconds</small>
                                    </div>
                                </div>
                            </div>

                            {{-- PRICE --}}
                            <div class="d-flex align-items-center mb-4">
                                <h3 class="fw-bold mb-0 me-3">
                                    ৳{{ number_format($dealProduct->discount_price ?? $dealProduct->base_price, 2) }}
                                </h3>

                                @if ($dealProduct->has_discount)
                                    <h5 class="text-decoration-line-through mb-0 opacity-75">
                                        ৳{{ number_format($dealProduct->base_price, 2) }}
                                    </h5>
                                    <span class="badge bg-danger ms-3">
                                        Save {{ $dealProduct->discount_percentage }}%
                                    </span>
                                @endif
                            </div>

                            <div class="d-flex gap-3">
                                <button class="btn btn-light btn-lg add-to-cart"
                                    data-product-id="{{ $dealProduct->id }}">
                                    <i class="fas fa-cart-plus me-2"></i> Add to Cart
                                </button>

                                <a href="{{ route('product.show', $dealProduct->slug) }}"
                                    class="btn btn-outline-light btn-lg">
                                    View Product
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-6 text-center">
                            <img src="{{ $dealProduct->primaryImage
                                ? asset('storage/' . $dealProduct->primaryImage->image_path)
                                : 'https://via.placeholder.com/500' }}"
                                class="img-fluid" alt="{{ $dealProduct->name }}">
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif


    <!-- Brands Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12">
                    <h2 class="fw-bold mb-3">Top Brands</h2>
                    <p class="text-muted">Shop from trusted brands</p>
                </div>
            </div>
            <div class="row">
                @php
                    $brands = [
                        [
                            'name' => 'Apple',
                            'logo' => 'https://upload.wikimedia.org/wikipedia/commons/f/fa/Apple_logo_black.svg',
                        ],
                        [
                            'name' => 'Samsung',
                            'logo' => 'https://upload.wikimedia.org/wikipedia/commons/2/24/Samsung_Logo.svg',
                        ],
                        [
                            'name' => 'Sony',
                            'logo' => 'https://upload.wikimedia.org/wikipedia/commons/c/ca/Sony_logo.svg',
                        ],
                        ['name' => 'LG', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/2/20/LG_symbol.svg'],
                        [
                            'name' => 'Dell',
                            'logo' => 'https://upload.wikimedia.org/wikipedia/commons/1/18/Dell_logo_2016.svg',
                        ],
                        [
                            'name' => 'HP',
                            'logo' => 'https://upload.wikimedia.org/wikipedia/commons/2/29/HP_New_Logo_2D.svg',
                        ],
                        [
                            'name' => 'Lenovo',
                            'logo' => 'https://upload.wikimedia.org/wikipedia/commons/4/4c/Lenovo_Global_logo.svg',
                        ],
                        [
                            'name' => 'Xiaomi',
                            'logo' => 'https://upload.wikimedia.org/wikipedia/commons/2/29/Xiaomi_logo.svg',
                        ],
                    ];
                @endphp

                @foreach ($brands as $brand)
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="brand-card h-100">
                            <img src="{{ $brand['logo'] }}" alt="{{ $brand['name'] }}" class="brand-img mb-3">
                            <h5 class="fw-bold mb-0">{{ $brand['name'] }}</h5>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="fas fa-shipping-fast"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Free Shipping</h4>
                        <p class="text-muted">Free delivery on orders above $99</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Secure Payment</h4>
                        <p class="text-muted">100% secure payment options</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h4 class="fw-bold mb-3">24/7 Support</h4>
                        <p class="text-muted">Dedicated customer support</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="fas fa-undo-alt"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Easy Returns</h4>
                        <p class="text-muted">30-day return policy</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter -->
    <section class="py-5">
        <div class="container">
            <div class="newsletter-section">
                <div class="row align-items-center">
                    <div class="col-lg-6 mb-4 mb-lg-0">
                        <h2 class="fw-bold mb-3">Subscribe to Newsletter</h2>
                        <p class="mb-4">Get the latest updates on new products and upcoming sales</p>
                    </div>
                    <div class="col-lg-6">
                        <form id="newsletterForm">
                            <div class="input-group">
                                <input type="email" class="form-control newsletter-input"
                                    placeholder="Enter your email" required>
                                <button class="btn btn-light px-4" type="submit">
                                    Subscribe <i class="fas fa-paper-plane ms-2"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Swiper
            const swiper = new Swiper('.mega-slider', {
                loop: true,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                effect: 'fade',
                fadeEffect: {
                    crossFade: true
                },
            });

            // Flash Sale Timer
            function updateFlashSaleTimer() {
                const endDate = new Date();
                endDate.setDate(endDate.getDate() + 3); // 3 days from now

                const countdown = setInterval(() => {
                    const now = new Date().getTime();
                    const distance = endDate - now;

                    if (distance < 0) {
                        clearInterval(countdown);
                        return;
                    }

                    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    document.getElementById('days').textContent = days.toString().padStart(2, '0');
                    document.getElementById('hours').textContent = hours.toString().padStart(2, '0');
                    document.getElementById('minutes').textContent = minutes.toString().padStart(2, '0');
                    document.getElementById('seconds').textContent = seconds.toString().padStart(2, '0');
                }, 1000);
            }

            // Deal of the Day Timer
            function updateDealTimer() {
                const countdown = setInterval(() => {
                    const hours = document.getElementById('deal-hours');
                    const minutes = document.getElementById('deal-minutes');
                    const seconds = document.getElementById('deal-seconds');

                    let h = parseInt(hours.textContent);
                    let m = parseInt(minutes.textContent);
                    let s = parseInt(seconds.textContent);

                    if (s > 0) {
                        s--;
                    } else {
                        if (m > 0) {
                            m--;
                            s = 59;
                        } else {
                            if (h > 0) {
                                h--;
                                m = 59;
                                s = 59;
                            } else {
                                clearInterval(countdown);
                                return;
                            }
                        }
                    }

                    hours.textContent = h.toString().padStart(2, '0');
                    minutes.textContent = m.toString().padStart(2, '0');
                    seconds.textContent = s.toString().padStart(2, '0');
                }, 1000);
            }

            // Add to Cart Functionality
            document.querySelectorAll('.add-to-cart').forEach(button => {
                button.addEventListener('click', function() {
                    const productId = this.getAttribute('data-product-id');
                    addToCart(productId, 1);
                });
            });

            // Quick View Functionality
            document.querySelectorAll('.quick-view-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const productId = this.getAttribute('data-product-id');
                    showQuickView(productId);
                });
            });

            // Newsletter Form
            document.getElementById('newsletterForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const email = this.querySelector('input[type="email"]').value;

                // Simulate API call
                setTimeout(() => {
                    Toast.fire({
                        icon: 'success',
                        title: 'Successfully subscribed to newsletter!'
                    });
                    this.reset();
                }, 1000);
            });

            // Initialize timers
            updateFlashSaleTimer();
            updateDealTimer();

            // Scroll animations
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animated');
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.animate-on-scroll').forEach(el => {
                observer.observe(el);
            });
        });

        function addToCart(productId, quantity) {
            @auth
            fetch('/cart/add', {
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
                            title: 'Product added to cart!'
                        });
                        updateCartCount(data.cart_count);
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: data.message || 'Failed to add to cart'
                        });
                    }
                });
        @else
            const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
            loginModal.show();

            Toast.fire({
                icon: 'warning',
                title: 'Please login to add items to cart'
            });
        @endauth
        }

        function showQuickView(productId) {
            // Here you would typically fetch product details via AJAX
            // For now, we'll redirect to the product page
            window.location.href = `/products/${productId}`;
        }

        function updateCartCount(count) {
            const cartCountElement = document.querySelector('.cart-count');
            if (cartCountElement) {
                cartCountElement.textContent = count;
                cartCountElement.style.display = count > 0 ? 'flex' : 'none';
            }
        }
    </script>



@endsection

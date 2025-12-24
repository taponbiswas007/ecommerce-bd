@extends('layouts.app')

@section('title', 'Mega Home - ElectroHub')

@section('styles')
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6f42c1;
            --accent-color: #20c997;
            --dark-color: #212529;
            --light-color: #f8f9fa;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
        }

        /* Hero Slider */
        .hero-slider {
            height: 600px;
            border-radius: 20px;
            overflow: hidden;
            position: relative;
        }

        .hero-slider img {
            height: 100%;
            object-fit: cover;
            width: 100%;
        }

        .slider-content {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.8), transparent);
            padding: 40px;
            color: white;
            z-index: 2;
        }

        .hero-slider .swiper-button-next,
        .hero-slider .swiper-button-prev {
            background: rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
            width: 50px;
            height: 50px;
            border-radius: 50%;
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.5);
            transition: all 0.3s ease;
        }

        .hero-slider .swiper-button-next:hover,
        .hero-slider .swiper-button-prev:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .hero-slider .swiper-button-next:after,
        .hero-slider .swiper-button-prev:after {
            font-size: 20px;
            font-weight: bold;
        }

        /* Improved Product Cards */
        .product-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            background: white;
            height: 100%;
            position: relative;
        }

        .product-card:hover {
            transform: translateY(-15px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .product-card a {
            text-decoration: none;
            color: inherit;
            display: block;
            height: 100%;
        }

        .product-img-container {
            position: relative;
            overflow: hidden;
            height: 250px;
            background: #f8f9fa;
        }

        .product-img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            transition: transform 0.5s ease;
            padding: 20px;
        }

        .product-card:hover .product-img {
            transform: scale(1.05);
        }

        .product-badges {
            position: absolute;
            top: 15px;
            left: 15px;
            right: 15px;
            z-index: 2;
            display: flex;
            justify-content: space-between;
        }

        .discount-badge {
            background: linear-gradient(135deg, var(--danger-color), #ff6b6b);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            box-shadow: 0 3px 10px rgba(220, 53, 69, 0.3);
        }

        .featured-badge {
            background: linear-gradient(135deg, var(--warning-color), #ffd166);
            color: var(--dark-color);
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            box-shadow: 0 3px 10px rgba(255, 193, 7, 0.3);
        }

        .new-badge {
            background: linear-gradient(135deg, var(--accent-color), #20c997);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            box-shadow: 0 3px 10px rgba(32, 201, 151, 0.3);
        }

        .product-actions {
            position: absolute;
            top: 15px;
            right: 15px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            opacity: 0;
            transform: translateX(20px);
            transition: all 0.3s ease;
        }

        .product-card:hover .product-actions {
            opacity: 1;
            transform: translateX(0);
        }

        .action-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: white;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--dark-color);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .action-btn:hover {
            background: var(--primary-color);
            color: white;
            transform: scale(1.1);
        }

        .wishlist-btn.active {
            color: var(--danger-color);
            background: #ffe6e6;
        }

        .product-content {
            padding: 20px;
            background: white;
        }

        .product-title {
            font-size: 15px;
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 8px;
            line-height: 1.4;
            height: 42px;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .product-category {
            font-size: 12px;
            color: #6c757d;
            margin-bottom: 10px;
        }

        .rating-container {
            display: flex;
            align-items: center;
            gap: 5px;
            margin-bottom: 15px;
        }

        .rating-stars {
            color: var(--warning-color);
            font-size: 14px;
        }

        .rating-count {
            font-size: 12px;
            color: #6c757d;
        }

        .product-price {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 15px;
        }

        .current-price {
            font-size: 18px;
            font-weight: 700;
            color: var(--primary-color);
        }

        .old-price {
            font-size: 14px;
            color: #6c757d;
            text-decoration: line-through;
        }

        .save-percent {
            background: var(--danger-color);
            color: white;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: bold;
        }

        .product-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }

        .add-to-cart-btn {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .add-to-cart-btn:hover {
            background: #0b5ed7;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
        }

        /* Category Cards */
        .category-slider .swiper-slide {
            width: 200px;
        }

        .category-card {
            background: white;
            border-radius: 15px;
            padding: 25px 15px;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border: 2px solid transparent;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .category-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
            border-color: var(--primary-color);
        }

        .category-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            color: white;
            font-size: 24px;
            overflow: hidden;
            position: relative;
            z-index: 1;
        }

        .category-icon img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Deal of the Day */
        .deal-of-the-day {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            overflow: hidden;
            color: white;
            margin: 40px 0;
            position: relative;
            overflow: hidden;
        }

        .deal-of-the-day::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0,0 L100,0 L100,100 Z" fill="rgba(255,255,255,0.05)"/></svg>');
            background-size: cover;
        }

        .deal-timer {
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .timer-unit {
            text-align: center;
        }

        .timer-value {
            background: rgba(0, 0, 0, 0.5);
            color: white;
            padding: 15px;
            border-radius: 10px;
            font-size: 2rem;
            font-weight: bold;
            min-width: 70px;
            display: inline-block;
            margin-bottom: 5px;
            border: 2px solid rgba(255, 255, 255, 0.2);
        }

        .timer-label {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.8);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Quick View Modal */
        .quick-view-modal .modal-dialog {
            max-width: 900px;
            margin: 1rem auto;
        }

        .quick-view-modal .modal-content {
            border-radius: 20px;
            overflow: hidden;
            border: none;
        }

        .product-gallery-slider {
            height: 400px;
            position: relative;
        }

        .product-gallery-slider img,
        .product-gallery-slider video {
            width: 100%;
            height: 100%;
            object-fit: contain;
            background: #f8f9fa;
        }

        .gallery-thumbs {
            margin-top: 10px;
            padding: 10px 0;
        }

        .gallery-thumbs .swiper-slide {
            opacity: 0.4;
            cursor: pointer;
            border: 2px solid transparent;
            border-radius: 8px;
            overflow: hidden;
            height: 80px;
        }

        .gallery-thumbs .swiper-slide-thumb-active {
            opacity: 1;
            border-color: var(--primary-color);
        }

        .gallery-thumbs img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .video-thumb {
            position: relative;
        }

        .video-thumb::after {
            content: '\f144';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 24px;
            text-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
        }

        /* Section Headers */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .section-header h2 {
            font-weight: 700;
            margin: 0;
            font-size: 28px;
            position: relative;
            padding-bottom: 10px;
        }

        .section-header h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background: var(--primary-color);
            border-radius: 2px;
        }

        .view-all-btn {
            padding: 10px 25px;
            border-radius: 25px;
            font-weight: 500;
            background: white;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
            transition: all 0.3s ease;
        }

        .view-all-btn:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
        }

        /* Swiper Navigation */
        .swiper-button-next,
        .swiper-button-prev {
            background: white;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .swiper-button-next:after,
        .swiper-button-prev:after {
            font-size: 16px;
            color: var(--dark-color);
            font-weight: bold;
        }

        .swiper-button-next:hover,
        .swiper-button-prev:hover {
            background: var(--primary-color);
            box-shadow: 0 8px 25px rgba(13, 110, 253, 0.3);
        }

        .swiper-button-next:hover:after,
        .swiper-button-prev:hover:after {
            color: white;
        }

        /* Category Wise Sections */
        .category-wise-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 60px 0;
            margin: 40px 0;
            border-radius: 20px;
        }

        /* Best Deals */
        .best-deals-section {
            background: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%);
            color: white;
            padding: 60px 0;
            border-radius: 20px;
            margin: 40px 0;
            position: relative;
            overflow: hidden;
        }

        .best-deals-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0,100 L100,0 L100,100 Z" fill="rgba(255,255,255,0.05)"/></svg>');
            background-size: cover;
        }

        /* Brand Slider */
        .brand-slider .swiper-slide {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .brand-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            width: 100%;
            border: 2px solid transparent;
        }

        .brand-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            border-color: var(--primary-color);
        }

        .brand-img {
            height: 50px;
            object-fit: contain;
            filter: grayscale(100%);
            opacity: 0.7;
            transition: all 0.3s ease;
        }

        .brand-card:hover .brand-img {
            filter: grayscale(0%);
            opacity: 1;
        }

        /* Newsletter */
        .newsletter-section {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 20px;
            padding: 50px;
            color: white;
            margin: 40px 0;
            position: relative;
            overflow: hidden;
        }

        .newsletter-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0,0 L100,0 L0,100 Z" fill="rgba(255,255,255,0.05)"/></svg>');
            background-size: cover;
        }

        .newsletter-input {
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.2);
            color: white;
            border-radius: 50px;
            padding: 15px 25px;
            width: 100%;
            transition: all 0.3s ease;
        }

        .newsletter-input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .newsletter-input:focus {
            background: rgba(255, 255, 255, 0.2);
            border-color: white;
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.1);
            color: white;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-slider {
                height: 400px;
            }

            .category-slider .swiper-slide {
                width: 150px;
            }

            .section-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .product-img-container {
                height: 200px;
            }

            .quick-view-modal .modal-dialog {
                margin: 0.5rem;
                max-width: calc(100% - 1rem);
            }

            .product-gallery-slider {
                height: 300px;
            }
        }

        @media (max-width: 576px) {
            .hero-slider {
                height: 300px;
            }

            .category-slider .swiper-slide {
                width: 120px;
            }

            .newsletter-section {
                padding: 30px 20px;
            }

            .deal-of-the-day {
                text-align: center;
            }

            .product-actions {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Stock Status */
        .stock-status {
            font-size: 12px;
            font-weight: 500;
            padding: 3px 10px;
            border-radius: 12px;
            display: inline-block;
        }

        .in-stock {
            background: #d1e7dd;
            color: #0f5132;
        }

        .low-stock {
            background: #fff3cd;
            color: #664d03;
        }

        .out-of-stock {
            background: #f8d7da;
            color: #842029;
        }
    </style>

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
@endsection

@section('content')
    <!-- Hero Slider Section -->
    <section class="py-4">
        <div class="container">
            <div class="swiper hero-slider">
                <div class="swiper-wrapper">
                    @for ($i = 1; $i <= 5; $i++)
                        <div class="swiper-slide position-relative">
                            <img src="https://images.unsplash.com/photo-1550009158-9ebf69173e03?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80"
                                alt="Hero Slide {{ $i }}">
                            <div class="slider-content">
                                <h1 class="display-4 fw-bold">Electronic Deals {{ $i }}</h1>
                                <p class="lead">Up to 50% OFF on selected items</p>
                                <a href="{{ route('shop') }}" class="btn btn-primary btn-lg mt-3">Shop Now</a>
                            </div>
                        </div>
                    @endfor
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>

    <!-- Category Slider Section -->
    <section class="py-5">
        <div class="container">
            <div class="section-header">
                <h2>Shop by Categories</h2>
                <a href="#" class="btn view-all-btn">
                    View All <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>

            <div class="swiper category-slider">
                <div class="swiper-wrapper">
                    @foreach ($categories as $category)
                        <div class="swiper-slide">
                            <a class=" text-decoration-none"
                                href="{{ route('category.show', $category->slug ?? strtolower(str_replace(' ', '-', $category->name))) }}">

                                <div class="category-card">
                                    <div class="category-icon">
                                        @if ($category->image)
                                            <img src="{{ asset('storage/' . $category->image) }}"
                                                alt="{{ $category->name }}">
                                        @else
                                            <i class="fas fa-box"></i>
                                        @endif
                                    </div>
                                    <h5 class="fw-bold mb-2">{{ $category->name }}</h5>
                                    <p class="text-muted small mb-0">{{ $category->products_count ?? 0 }} products</p>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>
    </section>

    <!-- Featured Products Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="section-header">
                <h2>Featured Products</h2>
                <a href="{{ route('shop') }}?featured=1" class="btn view-all-btn">
                    View All <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>

            <div class="swiper featured-slider">
                <div class="swiper-wrapper">
                    @foreach ($featuredProducts as $product)
                        <div class="swiper-slide">
                            <div class="product-card">
                                <a href="{{ route('product.show', $product->slug) }}" class="product-link">
                                    <div class="product-img-container">
                                        @php
                                            $image = $product->primaryImage ?? $product->images->first();
                                        @endphp
                                        <img src="{{ $image ? asset('storage/' . $image->image_path) : 'https://via.placeholder.com/300x200' }}"
                                            alt="{{ $product->name }}" class="product-img">

                                        <div class="product-badges">
                                            @if ($product->is_featured)
                                                <span class="featured-badge">Featured</span>
                                            @endif
                                            @if ($product->has_discount)
                                                <span class="discount-badge">{{ $product->discount_percentage }}%
                                                    OFF</span>
                                            @endif
                                        </div>

                                        <div class="product-actions">
                                            <button class="action-btn quick-view-btn"
                                                data-product-id="{{ $product->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button
                                                class="action-btn wishlist-btn {{ Auth::check() && $product->isInWishlist() ? 'active' : '' }}"
                                                data-product-id="{{ $product->id }}">
                                                <i class="fas fa-heart"></i>
                                            </button>
                                        </div>
                                    </div>
                                </a>
                                <div class="product-content">
                                    <div class="product-category">
                                        {{ $product->category->name ?? 'Uncategorized' }}
                                    </div>
                                    <h6 class="product-title">{{ Str::limit($product->name, 50) }}</h6>

                                    <div class="rating-container">
                                        <div class="rating-stars">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= floor($product->average_rating ?? 0))
                                                    <i class="fas fa-star"></i>
                                                @elseif ($i - 0.5 <= $product->average_rating ?? 0)
                                                    <i class="fas fa-star-half-alt"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="rating-count">({{ $product->total_reviews ?? 0 }})</span>
                                    </div>

                                    <div class="product-price">
                                        @if ($product->has_discount)
                                            <span
                                                class="current-price">৳{{ number_format($product->discount_price, 2) }}</span>
                                            <span class="old-price">৳{{ number_format($product->base_price, 2) }}</span>
                                            <span class="save-percent">Save {{ $product->discount_percentage }}%</span>
                                        @else
                                            <span
                                                class="current-price">৳{{ number_format($product->base_price, 2) }}</span>
                                        @endif
                                    </div>

                                    <div class="product-footer">
                                        <span
                                            class="stock-status {{ $product->stock_quantity > 10 ? 'in-stock' : ($product->stock_quantity > 0 ? 'low-stock' : 'out-of-stock') }}">
                                            {{ $product->stock_quantity > 10 ? 'In Stock' : ($product->stock_quantity > 0 ? 'Low Stock' : 'Out of Stock') }}
                                        </span>
                                        <button class="add-to-cart-btn" data-product-id="{{ $product->id }}">
                                            <i class="fas fa-cart-plus"></i> Add to Cart
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>
    </section>

    <!-- Deal of the Day -->
    @if ($dealProduct)
        <section class="py-5">
            <div class="container">
                <div class="deal-of-the-day">
                    <div class="row align-items-center">
                        <div class="col-lg-6 p-5 text-white position-relative">
                            <span class="badge bg-warning text-dark mb-3 px-3 py-2">
                                Deal of the Day
                            </span>

                            <h2 class="fw-bold mb-3">{{ $dealProduct->name }}</h2>
                            <p class="mb-4">
                                {{ Str::limit(strip_tags($dealProduct->short_description), 120) }}
                            </p>

                            <!-- TIMER -->
                            <div class="deal-timer mb-4"
                                data-deal-end="{{ optional($dealProduct->deal_end_at)->timestamp }}">
                                <p class="mb-3">Hurry up! Offer ends in:</p>
                                <div class="d-flex gap-3">
                                    <div class="timer-unit">
                                        <div class="timer-value hours">00</div>
                                        <div class="timer-label">Hours</div>
                                    </div>
                                    <div class="timer-unit">
                                        <div class="timer-value minutes">00</div>
                                        <div class="timer-label">Minutes</div>
                                    </div>
                                    <div class="timer-unit">
                                        <div class="timer-value seconds">00</div>
                                        <div class="timer-label">Seconds</div>
                                    </div>
                                </div>
                            </div>

                            <!-- PRICE -->
                            <div class="d-flex align-items-center mb-4 flex-wrap gap-3">
                                <h3 class="fw-bold mb-0">
                                    ৳{{ number_format($dealProduct->discount_price ?? $dealProduct->base_price, 2) }}
                                </h3>

                                @if ($dealProduct->has_discount)
                                    <h5 class="text-decoration-line-through mb-0 opacity-75">
                                        ৳{{ number_format($dealProduct->base_price, 2) }}
                                    </h5>
                                    <span class="badge bg-danger px-3 py-2">
                                        Save {{ $dealProduct->discount_percentage }}%
                                    </span>
                                @endif
                            </div>

                            <div class="d-flex gap-3 flex-wrap">
                                <button class="btn btn-light btn-lg add-to-cart"
                                    data-product-id="{{ $dealProduct->id }}">
                                    <i class="fas fa-cart-plus me-2"></i> Add to Cart
                                </button>
                                <a href="{{ route('product.show', $dealProduct->slug) }}"
                                    class="btn btn-outline-light btn-lg">
                                    View Product
                                </a>
                                <button
                                    class="btn btn-outline-light btn-lg wishlist-btn {{ Auth::check() && $dealProduct->isInWishlist() ? 'active' : '' }}"
                                    data-product-id="{{ $dealProduct->id }}">
                                    <i class="fas fa-heart me-2"></i> Wishlist
                                </button>
                            </div>
                        </div>

                        <div class="col-lg-6 text-center position-relative">
                            @if ($dealProduct->primaryImage)
                                <img src="{{ asset('storage/' . $dealProduct->primaryImage->image_path) }}"
                                    class="img-fluid rounded-3" alt="{{ $dealProduct->name }}"
                                    style="max-height: 400px;">
                            @else
                                <img src="https://via.placeholder.com/500x400" class="img-fluid rounded-3"
                                    alt="{{ $dealProduct->name }}">
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Category Wise Products Sections -->
    @foreach ($categories->take(3) as $category)
        <section class="category-wise-section">
            <div class="container">
                <div class="section-header">
                    <h2>{{ $category->name }}</h2>
                    <a href="{{ route('category.show', $category->slug ?? strtolower(str_replace(' ', '-', $category->name))) }}"
                        class="btn view-all-btn">
                        View All <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>

                @php
                    $categoryProducts = \App\Models\Product::with(['primaryImage', 'images'])
                        ->where('category_id', $category->id)
                        ->where('is_active', 1)
                        ->where('stock_quantity', '>', 0)
                        ->latest()
                        ->limit(8)
                        ->get();
                @endphp

                @if ($categoryProducts->count() > 0)
                    <div class="swiper category-products-slider" data-category="{{ $category->id }}">
                        <div class="swiper-wrapper">
                            @foreach ($categoryProducts as $product)
                                <div class="swiper-slide">
                                    <div class="product-card">
                                        <a href="{{ route('product.show', $product->slug) }}" class="product-link">
                                            <div class="product-img-container">
                                                @php
                                                    $image = $product->primaryImage ?? $product->images->first();
                                                @endphp
                                                <img src="{{ $image ? asset('storage/' . $image->image_path) : 'https://via.placeholder.com/300x200' }}"
                                                    alt="{{ $product->name }}" class="product-img">

                                                <div class="product-badges">
                                                    @if ($product->is_featured)
                                                        <span class="featured-badge">Featured</span>
                                                    @endif
                                                    @if ($product->has_discount)
                                                        <span class="discount-badge">{{ $product->discount_percentage }}%
                                                            OFF</span>
                                                    @endif
                                                </div>

                                                <div class="product-actions">
                                                    <button class="action-btn quick-view-btn"
                                                        data-product-id="{{ $product->id }}">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button
                                                        class="action-btn wishlist-btn {{ Auth::check() && $product->isInWishlist() ? 'active' : '' }}"
                                                        data-product-id="{{ $product->id }}">
                                                        <i class="fas fa-heart"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </a>
                                        <div class="product-content">
                                            <div class="product-category">
                                                {{ $product->category->name ?? 'Uncategorized' }}
                                            </div>
                                            <h6 class="product-title">{{ Str::limit($product->name, 50) }}</h6>

                                            <div class="rating-container">
                                                <div class="rating-stars">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= floor($product->average_rating ?? 0))
                                                            <i class="fas fa-star"></i>
                                                        @elseif ($i - 0.5 <= $product->average_rating ?? 0)
                                                            <i class="fas fa-star-half-alt"></i>
                                                        @else
                                                            <i class="far fa-star"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <span class="rating-count">({{ $product->total_reviews ?? 0 }})</span>
                                            </div>

                                            <div class="product-price">
                                                @if ($product->has_discount)
                                                    <span
                                                        class="current-price">৳{{ number_format($product->discount_price, 2) }}</span>
                                                    <span
                                                        class="old-price">৳{{ number_format($product->base_price, 2) }}</span>
                                                    <span class="save-percent">Save
                                                        {{ $product->discount_percentage }}%</span>
                                                @else
                                                    <span
                                                        class="current-price">৳{{ number_format($product->base_price, 2) }}</span>
                                                @endif
                                            </div>

                                            <div class="product-footer">
                                                <span
                                                    class="stock-status {{ $product->stock_quantity > 10 ? 'in-stock' : ($product->stock_quantity > 0 ? 'low-stock' : 'out-of-stock') }}">
                                                    {{ $product->stock_quantity > 10 ? 'In Stock' : ($product->stock_quantity > 0 ? 'Low Stock' : 'Out of Stock') }}
                                                </span>
                                                <button class="add-to-cart-btn" data-product-id="{{ $product->id }}">
                                                    <i class="fas fa-cart-plus"></i> Add to Cart
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <p class="text-muted">No products available in this category yet.</p>
                    </div>
                @endif
            </div>
        </section>
    @endforeach

    <!-- Best Deals Section -->
    <section class="best-deals-section">
        <div class="container position-relative">
            <div class="section-header">
                <h2 class="text-white">Best Deals</h2>
                <a href="{{ route('shop') }}?deal=1" class="btn btn-outline-light view-all-btn">
                    View All <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>

            @php
                $bestDeals = \App\Models\Product::with(['primaryImage', 'images'])
                    ->where('is_active', 1)
                    ->where('stock_quantity', '>', 0)
                    ->whereNotNull('discount_price')
                    ->whereColumn('discount_price', '<', 'base_price')
                    ->orderBy('created_at', 'desc')
                    ->limit(6)
                    ->get();
            @endphp

            <div class="swiper best-deals-slider">
                <div class="swiper-wrapper">
                    @foreach ($bestDeals as $product)
                        <div class="swiper-slide">
                            <div class="product-card">
                                <a href="{{ route('product.show', $product->slug) }}" class="product-link">
                                    <div class="product-img-container">
                                        @php
                                            $image = $product->primaryImage ?? $product->images->first();
                                        @endphp
                                        <img src="{{ $image ? asset('storage/' . $image->image_path) : 'https://via.placeholder.com/300x200' }}"
                                            alt="{{ $product->name }}" class="product-img">

                                        <div class="product-badges">
                                            @if ($product->is_featured)
                                                <span class="featured-badge">Featured</span>
                                            @endif
                                            @if ($product->has_discount)
                                                <span class="discount-badge">{{ $product->discount_percentage }}%
                                                    OFF</span>
                                            @endif
                                        </div>

                                        <div class="product-actions">
                                            <button class="action-btn quick-view-btn"
                                                data-product-id="{{ $product->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button
                                                class="action-btn wishlist-btn {{ Auth::check() && $product->isInWishlist() ? 'active' : '' }}"
                                                data-product-id="{{ $product->id }}">
                                                <i class="fas fa-heart"></i>
                                            </button>
                                        </div>
                                    </div>
                                </a>
                                <div class="product-content">
                                    <div class="product-category text-white-50">
                                        {{ $product->category->name ?? 'Uncategorized' }}
                                    </div>
                                    <h6 class="product-title text-white">{{ Str::limit($product->name, 50) }}</h6>

                                    <div class="rating-container">
                                        <div class="rating-stars">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= floor($product->average_rating ?? 0))
                                                    <i class="fas fa-star"></i>
                                                @elseif ($i - 0.5 <= $product->average_rating ?? 0)
                                                    <i class="fas fa-star-half-alt"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <span
                                            class="rating-count text-white-50">({{ $product->total_reviews ?? 0 }})</span>
                                    </div>

                                    <div class="product-price">
                                        <span
                                            class="current-price text-white">৳{{ number_format($product->discount_price, 2) }}</span>
                                        <span
                                            class="old-price text-white-50">৳{{ number_format($product->base_price, 2) }}</span>
                                        <span class="save-percent bg-white text-danger">Save
                                            {{ $product->discount_percentage }}%</span>
                                    </div>

                                    <div class="product-footer">
                                        <span
                                            class="stock-status {{ $product->stock_quantity > 10 ? 'in-stock' : ($product->stock_quantity > 0 ? 'low-stock' : 'out-of-stock') }}">
                                            {{ $product->stock_quantity > 10 ? 'In Stock' : ($product->stock_quantity > 0 ? 'Low Stock' : 'Out of Stock') }}
                                        </span>
                                        <button class="add-to-cart-btn bg-white text-danger"
                                            data-product-id="{{ $product->id }}">
                                            <i class="fas fa-cart-plus"></i> Add to Cart
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>
    </section>

    <!-- Brand Slider Section -->
    <section class="py-5">
        <div class="container">
            <div class="section-header">
                <h2>Top Brands</h2>
            </div>

            <div class="swiper brand-slider">
                <div class="swiper-wrapper">
                    @foreach ($brands as $brand)
                        <div class="swiper-slide">
                            <div class="brand-card">
                                @if ($brand->logo)
                                    <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}"
                                        class="brand-img mb-3">
                                @else
                                    <div class="brand-img mb-3 d-flex align-items-center justify-content-center">
                                        <i class="fas fa-building fa-2x text-muted"></i>
                                    </div>
                                @endif
                                <h5 class="fw-bold mb-0">{{ $brand->name }}</h5>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="newsletter-section">
        <div class="container position-relative">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h2 class="fw-bold mb-3">Stay Updated</h2>
                    <p class="mb-0">Subscribe to our newsletter and get 10% off your first order</p>
                </div>
                <div class="col-lg-6">
                    <form id="newsletterForm" class="d-flex">
                        <input type="email" class="form-control newsletter-input me-2" placeholder="Enter your email"
                            required>
                        <button class="btn btn-light px-4" type="submit">
                            Subscribe <i class="fas fa-paper-plane ms-2"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick View Modal -->
    <div class="modal fade quick-view-modal" id="quickViewModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-0">
                    <div class="row">
                        <div class="col-lg-6 mb-4 mb-lg-0">
                            <!-- Main Gallery Slider -->
                            <div class="swiper product-gallery-slider">
                                <div class="swiper-wrapper" id="gallery-slides">
                                    <!-- Slides will be loaded via AJAX -->
                                </div>
                                <div class="swiper-button-next"></div>
                                <div class="swiper-button-prev"></div>
                            </div>

                            <!-- Thumbnail Gallery -->
                            <div class="swiper gallery-thumbs mt-3">
                                <div class="swiper-wrapper" id="gallery-thumbs">
                                    <!-- Thumbs will be loaded via AJAX -->
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div id="product-details">
                                <!-- Product details will be loaded via AJAX -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
                // Initialize all Swiper sliders
                const sliders = {
                    hero: new Swiper('.hero-slider', {
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
                    }),

                    category: new Swiper('.category-slider', {
                        slidesPerView: 2,
                        spaceBetween: 15,
                        navigation: {
                            nextEl: '.swiper-button-next',
                            prevEl: '.swiper-button-prev',
                        },
                        breakpoints: {
                            576: {
                                slidesPerView: 3
                            },
                            768: {
                                slidesPerView: 4
                            },
                            992: {
                                slidesPerView: 5
                            },
                            1200: {
                                slidesPerView: 6
                            }
                        }
                    }),

                    featured: new Swiper('.featured-slider', {
                        slidesPerView: 2,
                        spaceBetween: 20,
                        navigation: {
                            nextEl: '.swiper-button-next',
                            prevEl: '.swiper-button-prev',
                        },
                        breakpoints: {
                            576: {
                                slidesPerView: 2
                            },
                            768: {
                                slidesPerView: 3
                            },
                            992: {
                                slidesPerView: 4
                            },
                            1200: {
                                slidesPerView: 5
                            }
                        }
                    }),

                    bestDeals: new Swiper('.best-deals-slider', {
                        slidesPerView: 2,
                        spaceBetween: 20,
                        navigation: {
                            nextEl: '.swiper-button-next',
                            prevEl: '.swiper-button-prev',
                        },
                        breakpoints: {
                            576: {
                                slidesPerView: 2
                            },
                            768: {
                                slidesPerView: 3
                            },
                            992: {
                                slidesPerView: 4
                            },
                            1200: {
                                slidesPerView: 5
                            }
                        }
                    }),

                    brand: new Swiper('.brand-slider', {
                        slidesPerView: 2,
                        spaceBetween: 20,
                        navigation: {
                            nextEl: '.swiper-button-next',
                            prevEl: '.swiper-button-prev',
                        },
                        breakpoints: {
                            576: {
                                slidesPerView: 3
                            },
                            768: {
                                slidesPerView: 4
                            },
                            992: {
                                slidesPerView: 5
                            },
                            1200: {
                                slidesPerView: 6
                            }
                        }
                    })
                };

                // Initialize category product sliders
                document.querySelectorAll('.category-products-slider').forEach(slider => {
                    new Swiper(slider, {
                        slidesPerView: 2,
                        spaceBetween: 20,
                        navigation: {
                            nextEl: slider.querySelector('.swiper-button-next'),
                            prevEl: slider.querySelector('.swiper-button-prev'),
                        },
                        breakpoints: {
                            576: {
                                slidesPerView: 2
                            },
                            768: {
                                slidesPerView: 3
                            },
                            992: {
                                slidesPerView: 4
                            },
                            1200: {
                                slidesPerView: 5
                            }
                        }
                    });
                });

                // Deal of the Day Timer
                function updateDealTimer() {
                    const timer = document.querySelector('[data-deal-end]');
                    if (!timer) return;

                    const endTimestamp = parseInt(timer.dataset.dealEnd) * 1000;

                    function update() {
                        const diff = endTimestamp - Date.now();

                        if (diff <= 0) {
                            timer.querySelector('.hours').innerText = '00';
                            timer.querySelector('.minutes').innerText = '00';
                            timer.querySelector('.seconds').innerText = '00';
                            return;
                        }

                        const hours = Math.floor(diff / 3600000);
                        const minutes = Math.floor((diff / 60000) % 60);
                        const seconds = Math.floor((diff / 1000) % 60);

                        timer.querySelector('.hours').innerText = hours.toString().padStart(2, '0');
                        timer.querySelector('.minutes').innerText = minutes.toString().padStart(2, '0');
                        timer.querySelector('.seconds').innerText = seconds.toString().padStart(2, '0');
                    }

                    update();
                    setInterval(update, 1000);
                }

                // Event Delegation for Click Events
                document.addEventListener('click', function(e) {
                    // Add to Cart
                    if (e.target.closest('.add-to-cart') || e.target.closest('.add-to-cart-btn')) {
                        e.preventDefault();
                        const button = e.target.closest('.add-to-cart') || e.target.closest('.add-to-cart-btn');
                        const productId = button.getAttribute('data-product-id');
                        addToCart(productId, 1);
                    }

                    // Quick View
                    if (e.target.closest('.quick-view-btn')) {
                        e.preventDefault();
                        e.stopPropagation();
                        const button = e.target.closest('.quick-view-btn');
                        const productId = button.getAttribute('data-product-id');
                        showQuickView(productId);
                    }

                    // Wishlist
                    if (e.target.closest('.wishlist-btn')) {
                        e.preventDefault();
                        e.stopPropagation();
                        const button = e.target.closest('.wishlist-btn');
                        const productId = button.getAttribute('data-product-id');
                        toggleWishlist(productId, button);
                    }

                    // Product Card Click (but not on buttons)
                    if (e.target.closest('.product-link') && !e.target.closest('.product-actions')) {
                        e.preventDefault();
                        const link = e.target.closest('.product-link');
                        window.location.href = link.href;
                    }
                });


                // Quick View Function
                async function showQuickView(productId) {
                    try {
                        // FIXED URL: Use the correct route
                        const response = await fetch(`/product/quick-view/${productId}`);
                        const data = await response.json();

                        if (data.success) {
                            const modal = new bootstrap.Modal(document.getElementById('quickViewModal'));

                            // Clear previous content
                            document.getElementById('gallery-slides').innerHTML = '';
                            document.getElementById('gallery-thumbs').innerHTML = '';
                            document.getElementById('product-details').innerHTML = '';

                            // Load the HTML from the response
                            document.getElementById('product-details').innerHTML = data.html;

                            // Load product images from the data
                            if (data.product && data.product.images) {
                                loadProductImages(data.product.images, data.product.video_url);
                            }

                            modal.show();

                            // Initialize gallery slider after modal is shown
                            setTimeout(() => {
                                initGallerySlider();
                            }, 100);
                        }
                    } catch (error) {
                        console.error('Error loading quick view:', error);
                        Toast.fire({
                            icon: 'error',
                            title: 'Failed to load product details'
                        });
                    }
                }

                // Load Product Images (simplified - uses data from response)
                function loadProductImages(images, videoUrl = null) {
                    const slidesContainer = document.getElementById('gallery-slides');
                    const thumbsContainer = document.getElementById('gallery-thumbs');

                    slidesContainer.innerHTML = '';
                    thumbsContainer.innerHTML = '';

                    // Add images
                    images.forEach((image, index) => {
                        const slide = document.createElement('div');
                        slide.className = 'swiper-slide';
                        slide.innerHTML =
                            `<img src="/storage/${image.image_path}" alt="${image.alt_text}" class="img-fluid">`;
                        slidesContainer.appendChild(slide);

                        const thumb = document.createElement('div');
                        thumb.className = 'swiper-slide';
                        thumb.innerHTML =
                            `<img src="/storage/${image.image_path}" alt="${image.alt_text}" class="img-fluid" style="height: 80px; object-fit: cover;">`;
                        thumbsContainer.appendChild(thumb);
                    });

                    // Add video if exists
                    if (videoUrl) {
                        const videoSlide = document.createElement('div');
                        videoSlide.className = 'swiper-slide';
                        videoSlide.innerHTML = `
            <div class="ratio ratio-16x9 h-100">
                <iframe src="${videoUrl}"
                        title="Product Video"
                        allowfullscreen
                        class="w-100 h-100">
                </iframe>
            </div>
        `;
                        slidesContainer.appendChild(videoSlide);

                        const videoThumb = document.createElement('div');
                        videoThumb.className = 'swiper-slide video-thumb';
                        videoThumb.innerHTML = `
            <div class="w-100 h-100 bg-dark d-flex align-items-center justify-content-center">
                <i class="fas fa-play-circle fa-2x text-white"></i>
            </div>
        `;
                        thumbsContainer.appendChild(videoThumb);
                    }
                }

                // Initialize Gallery Slider
                function initGallerySlider() {
                    const gallerySlider = new Swiper('.product-gallery-slider', {
                        spaceBetween: 10,
                        navigation: {
                            nextEl: '.product-gallery-slider .swiper-button-next',
                            prevEl: '.product-gallery-slider .swiper-button-prev',
                        },
                        thumbs: {
                            swiper: {
                                el: '.gallery-thumbs',
                                slidesPerView: 4,
                                spaceBetween: 10,
                                freeMode: true,
                                watchSlidesProgress: true,
                            },
                        },
                    });
                }

                // Wishlist Function
                function toggleWishlist(productId, button) {
                    @auth
                    fetch('{{ route('wishlist.toggle', ':id') }}'.replace(':id', productId), {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                product_id: productId
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                button.classList.toggle('active');
                                Toast.fire({
                                    icon: 'success',
                                    title: data.message
                                });

                                // Update wishlist count
                                const wishlistCount = document.querySelector('.wishlist-count');
                                if (wishlistCount) {
                                    wishlistCount.textContent = data.wishlist_count;
                                }
                            } else {
                                Toast.fire({
                                    icon: 'error',
                                    title: data.message
                                });
                            }
                        });
                @else
                    const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
                    loginModal.show();

                    Toast.fire({
                        icon: 'warning',
                        title: 'Please login to add to wishlist'
                    });
                @endauth
            }

            // Add to Cart Function
            function addToCart(productId, quantity) {
                @auth
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
                                title: 'Product added to cart!'
                            });
                            updateCartCount(data.cart_count);
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: data.message || 'Failed to add to cart'
                            });
                        }
                    })
                    .catch(error => {
                        Toast.fire({
                            icon: 'error',
                            title: 'Network error. Please try again.'
                        });
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

        // Update Cart Count
        function updateCartCount(count) {
            const cartCountElement = document.querySelector('.cart-count');
            if (cartCountElement) {
                cartCountElement.textContent = count;
                cartCountElement.style.display = count > 0 ? 'flex' : 'none';
            }
        }

        // Newsletter Form
        document.getElementById('newsletterForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input[type="email"]').value;
            const button = this.querySelector('button');
            const originalText = button.innerHTML;

            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            button.disabled = true;

            // Simulate API call
            setTimeout(() => {
                Toast.fire({
                    icon: 'success',
                    title: 'Successfully subscribed to newsletter!'
                });
                this.reset();
                button.innerHTML = originalText;
                button.disabled = false;
            }, 1500);
        });

        // Initialize Deal Timer
        updateDealTimer();
        });
    </script>

    <!-- Toast Notification -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
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
    </script>
@endsection

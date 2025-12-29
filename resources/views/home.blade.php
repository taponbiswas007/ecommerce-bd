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
            min-height: 480px;
            position: relative;
            display: flex;
            flex-direction: column;
        }

        .product-card:hover {
            /* transform: translateY(-15px); */
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.15);
        }

        .product-card a {
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .product-img-container {
            position: relative;
            overflow: hidden;
            height: 280px;
            background: #f8f9fa;
            flex-shrink: 0;
        }

        .product-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .product-card:hover .product-img {
            transform: scale(1.08);
        }

        .product-badges {
            position: absolute;
            top: 12px;
            left: 12px;
            right: 12px;
            z-index: 2;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 8px;
        }

        .discount-badge {
            background: linear-gradient(135deg, var(--danger-color), #ff6b6b);
            color: white;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            box-shadow: 0 3px 10px rgba(220, 53, 69, 0.4);
            backdrop-filter: blur(4px);
        }

        .featured-badge {
            background: linear-gradient(135deg, var(--warning-color), #ffd166);
            color: var(--dark-color);
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            box-shadow: 0 3px 10px rgba(255, 193, 7, 0.4);
            backdrop-filter: blur(4px);
        }

        .new-badge {
            background: linear-gradient(135deg, var(--accent-color), #20c997);
            color: white;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            box-shadow: 0 3px 10px rgba(32, 201, 151, 0.4);
            backdrop-filter: blur(4px);
        }

        .product-actions {
            position: absolute;
            bottom: 20px;
            right: 12px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.3s ease;
            z-index: 3;
        }

        .product-card:hover .product-actions {
            opacity: 1;
            transform: translateY(0);
        }

        .action-btn {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: white;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--dark-color);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
            cursor: pointer;
            font-size: 18px;
        }

        .action-btn:hover {
            background: var(--primary-color);
            color: white;
            transform: scale(1.15);
            box-shadow: 0 6px 20px rgba(13, 110, 253, 0.4);
        }

        .wishlist-btn.active {
            color: white;
            background: var(--danger-color);
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4);
        }

        .wishlist-btn.active:hover {
            background: #c82333;
            transform: scale(1.15);
        }

        .product-content {
            padding: 14px;
            background: white;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 0;
        }

        .product-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 8px;
            line-height: 1.3;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            transition: color 0.3s ease;
            cursor: pointer;
        }

        .product-content a .product-title:hover {
            color: var(--primary-color);
        }

        .product-category {
            font-size: 11px;
            color: #999;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .product-details-toggle {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-bottom: 10px;
        }

        .product-description {
            font-size: 12px;
            color: #555;
            line-height: 1.4;
            max-height: 52px;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
        }

        .product-description.hidden {
            display: block;
        }

        .rating-container {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .rating-container.hidden {
            display: flex;
        }

        .rating-stars {
            color: var(--warning-color);
            font-size: 13px;
            letter-spacing: 1px;
        }

        .rating-count {
            font-size: 11px;
            color: #999;
        }

        .price-marquee {
            position: relative;
            overflow: hidden;
            white-space: nowrap;
            margin-bottom: 6px;
        }

        .price-marquee-inner,
        .price-marquee-dup {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .price-marquee.marquee .price-marquee-inner,
        .price-marquee.marquee .price-marquee-dup {
            animation: marquee-slide 14s linear infinite;
        }

        .price-marquee.marquee:hover .price-marquee-inner,
        .price-marquee.marquee:hover .price-marquee-dup {
            animation-play-state: paused;
        }

        .price-marquee-dup {
            margin-left: 24px;
        }

        @keyframes marquee-slide {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-100%);
            }
        }

        .price-chip {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 8px;
            border-radius: 6px;
            background: #f1f3f5;
            font-size: 12px;
            font-weight: 600;
            color: #0d6efd;
            border: 1px solid #e5e7eb;
            white-space: nowrap;
        }

        .price-chip.old {
            background: transparent;
            color: #888;
            text-decoration: line-through;
            border: none;
            font-weight: 500;
        }

        .price-chip.tier {
            color: #0f5132;
            background: #e8f5e9;
            border-color: #d1e7dd;
            font-weight: 600;
        }

        .tiered-pricing {
            display: flex;
            flex-direction: column;
            gap: 4px;
            padding: 6px 8px;
            background: #f8f9fa;
            border-radius: 6px;
            max-height: 80px;
            overflow-y: auto;
            scrollbar-width: thin;
        }

        .tiered-pricing::-webkit-scrollbar {
            width: 4px;
        }

        .tiered-pricing::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 4px;
        }

        .tiered-price-item {
            display: flex;
            align-items: baseline;
            gap: 4px;
            flex-wrap: wrap;
        }

        .tiered-price-value {
            font-size: 12px;
            font-weight: 700;
            color: #0d6efd;
        }

        .tiered-price-qty {
            font-size: 10px;
            color: #666;
            font-weight: 500;
        }

        .save-percent {
            background: var(--danger-color);
            color: white;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: bold;
        }

        .product-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 10px;
            margin-top: auto;
            border-top: 1px solid #e9ecef;
            gap: 8px;
        }

        .product-footer-left {
            display: flex;
            align-items: center;
            gap: 8px;
            flex: 1;
        }

        .product-footer-right {
            display: flex;
            align-items: center;
            gap: 10px;
            flex: 0 0 auto;
        }

        .add-to-cart-btn {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 4px;
            white-space: nowrap;
        }

        .add-to-cart-btn:hover {
            background: #0b5ed7;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
        }

        .stock-status {
            font-size: 11px;
            font-weight: 600;
            padding: 4px 10px;
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

        /* Category Cards */
        .category-slider .swiper-slide {
            /* width: 200px; */
            height: 200px;
            padding: 15px 0;
        }

        .category-card {
            position: relative;
            height: 100%;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
        }

        .category-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.6), transparent);
            z-index: 1;
        }

        .category-icon {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 0;
            border-radius: 15px;
            overflow: hidden;
        }

        .category-icon img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .category-icon i {
            font-size: 48px;
            color: #ddd;
        }

        .category-card h5 {
            position: relative;
            z-index: 2;
            color: white;
            margin: 0 15px 5px 15px !important;
            font-size: 16px;
            font-weight: 700;
        }

        .category-card p {
            position: relative;
            z-index: 2;
            color: rgba(255, 255, 255, 0.9);
            margin: 0 15px 15px 15px !important;
            font-size: 13px;
        }

        .category-card:hover {
            transform: scale(1.01);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);

        }

        .category-card:hover h5,
        .category-card:hover p {
            color: white;
        }

        .category-icon img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .category-products-slider .swiper-slide {
            height: auto;
            padding: 20px 0;
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
            width: 40px !important;
            height: 40px !important;
            border-radius: 50%;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .swiper-button-next:after,
        .swiper-button-prev:after {
            font-size: 20px !important;
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
                    @forelse ($heroProducts as $product)
                        @php
                            $image = $product->primaryImage ?? $product->images->first();
                            $imageUrl = $image
                                ? asset('storage/' . $image->image_path)
                                : 'https://via.placeholder.com/1200x600';
                            $finalPrice = $product->discount_price ?? $product->base_price;
                        @endphp
                        <div class="swiper-slide position-relative">
                            <img src="{{ $imageUrl }}" alt="{{ $product->name }}">
                            <div class="slider-content">
                                <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                                    <span class="badge bg-light text-dark">
                                        {{ $product->category->name ?? 'All Products' }}
                                    </span>
                                    @if ($product->discount_price)
                                        <span class="badge bg-danger">Save {{ $product->discount_percentage }}%</span>
                                    @endif
                                </div>
                                <h1 class="display-5 fw-bold">{{ $product->name }}</h1>
                                <p class="lead">{{ Str::limit(strip_tags($product->short_description ?? ''), 140) }}</p>
                                <div class="d-flex align-items-center gap-3 flex-wrap">
                                    <span class="fs-3 fw-bold">৳{{ number_format($finalPrice, 0) }}</span>
                                    @if ($product->discount_price)
                                        <span
                                            class="text-decoration-line-through opacity-75">৳{{ number_format($product->base_price, 0) }}</span>
                                    @endif
                                </div>
                                <div class="mt-4 d-flex gap-3 flex-wrap">
                                    <a href="{{ route('shop') }}" class="btn btn-primary btn-lg">
                                        Shop Now
                                    </a>
                                    <a href="{{ route('product.show', $product->slug) }}"
                                        class="btn btn-outline-light btn-lg">
                                        View Product
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        @for ($i = 1; $i <= 3; $i++)
                            <div class="swiper-slide position-relative">
                                <img src="https://images.unsplash.com/photo-1550009158-9ebf69173e03?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80"
                                    alt="Hero Slide {{ $i }}">
                                <div class="slider-content">
                                    <h1 class="display-4 fw-bold">Electronic Deals</h1>
                                    <p class="lead">Explore our latest offers across all categories</p>
                                    <a href="{{ route('shop') }}" class="btn btn-primary btn-lg mt-3">Shop Now</a>
                                </div>
                            </div>
                        @endfor
                    @endforelse
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
                            <a class="text-decoration-none"
                                href="{{ route('category.show', $category->slug ?? strtolower(str_replace(' ', '-', $category->name))) }}">

                                <div class="category-card"
                                    style="background-image: url('{{ $category->image ? asset('storage/' . $category->image) : 'data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22200%22 height=%22250%22%3E%3Crect fill=%22%23f0f0f0%22 width=%22200%22 height=%22250%22/%3E%3C/svg%3E' }}');">
                                    @if (!$category->image)
                                        <div class="category-icon">
                                            <i class="fas fa-box"></i>
                                        </div>
                                    @endif
                                    <h5 class="fw-bold">{{ $category->name }}</h5>
                                    <p class="text-white small mb-0">{{ $category->products_count ?? 0 }} products</p>
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
                                    <a href="{{ route('product.show', $product->slug) }}" class="text-decoration-none">
                                        <h6 class="product-title">{{ $product->name }}</h6>
                                    </a>

                                    <div class="product-details-toggle">
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
                                        <div class="product-description">
                                            {{ Str::limit(strip_tags($product->short_description ?? ''), 80) }}
                                        </div>
                                    </div>
                                    <!-- Price Line (single marquee) -->
                                    @php
                                        $tieredPrices = $product->prices()->orderBy('min_quantity', 'asc')->get();
                                        $unit = $product->unit ? $product->unit->symbol : '';
                                    @endphp
                                    <div class="price-marquee" data-price-marquee>
                                        <div class="price-marquee-inner">
                                            @if ($product->has_discount)
                                                <span
                                                    class="price-chip main">৳{{ number_format($product->discount_price, 0) }}</span>
                                                <span
                                                    class="price-chip old">৳{{ number_format($product->base_price, 0) }}</span>
                                            @else
                                                <span
                                                    class="price-chip main">৳{{ number_format($product->base_price, 0) }}</span>
                                            @endif

                                            @foreach ($tieredPrices as $price)
                                                <span class="price-chip tier">৳{{ number_format($price->price, 0) }}
                                                    <small>({{ $price->min_quantity }}{{ $price->max_quantity ? ' - ' . $price->max_quantity : '+' }}{{ $unit ? ' ' . $unit : '' }})</small></span>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Footer: Stock & Add to Cart -->
                                    <div class="product-footer">
                                        <span
                                            class="stock-status {{ $product->stock_quantity > 10 ? 'in-stock' : ($product->stock_quantity > 0 ? 'low-stock' : 'out-of-stock') }}">
                                            {{ $product->stock_quantity > 10 ? 'In Stock' : ($product->stock_quantity > 0 ? 'Low Stock' : 'Out') }}
                                        </span>
                                        <button class="add-to-cart-btn" data-product-id="{{ $product->id }}">
                                            <i class="fas fa-cart-plus"></i> Cart
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
                    $categoryProducts = \App\Models\Product::with(['primaryImage', 'images', 'unit'])
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
                                            <a href="{{ route('product.show', $product->slug) }}"
                                                class="text-decoration-none">
                                                <h6 class="product-title">{{ $product->name }}</h6>
                                            </a>

                                            <div class="product-details-toggle">
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
                                                <div class="product-description">
                                                    {{ Str::limit(strip_tags($product->short_description ?? ''), 80) }}
                                                </div>
                                            </div>

                                            <!-- Price Line (single marquee) -->
                                            @php
                                                $tieredPrices = $product
                                                    ->prices()
                                                    ->orderBy('min_quantity', 'asc')
                                                    ->get();
                                                $unit = $product->unit ? $product->unit->symbol : '';
                                            @endphp
                                            <div class="price-marquee" data-price-marquee>
                                                <div class="price-marquee-inner">
                                                    @if ($product->has_discount)
                                                        <span
                                                            class="price-chip main">৳{{ number_format($product->discount_price, 0) }}</span>
                                                        <span
                                                            class="price-chip old">৳{{ number_format($product->base_price, 0) }}</span>
                                                    @else
                                                        <span
                                                            class="price-chip main">৳{{ number_format($product->base_price, 0) }}</span>
                                                    @endif

                                                    @foreach ($tieredPrices as $price)
                                                        <span
                                                            class="price-chip tier">৳{{ number_format($price->price, 0) }}
                                                            <small>({{ $price->min_quantity }}{{ $price->max_quantity ? ' - ' . $price->max_quantity : '+' }}{{ $unit ? ' ' . $unit : '' }})</small></span>
                                                    @endforeach
                                                </div>
                                            </div>

                                            <!-- Footer: Stock & Add to Cart -->
                                            <div class="product-footer">
                                                <span
                                                    class="stock-status {{ $product->stock_quantity > 10 ? 'in-stock' : ($product->stock_quantity > 0 ? 'low-stock' : 'out-of-stock') }}">
                                                    {{ $product->stock_quantity > 10 ? 'In Stock' : ($product->stock_quantity > 0 ? 'Low Stock' : 'Out') }}
                                                </span>
                                                <button class="add-to-cart-btn" data-product-id="{{ $product->id }}">
                                                    <i class="fas fa-cart-plus"></i> Cart
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
                $bestDeals = \App\Models\Product::with(['primaryImage', 'images', 'unit'])
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
                                    <a href="{{ route('product.show', $product->slug) }}" class="text-decoration-none">
                                        <h6 class="product-title">{{ $product->name }}</h6>
                                    </a>

                                    <div class="product-details-toggle">
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
                                        <div class="product-description">
                                            {{ Str::limit(strip_tags($product->short_description ?? ''), 80) }}
                                        </div>
                                    </div>

                                    <!-- Price Line (single marquee) -->
                                    @php
                                        $tieredPrices = $product->prices()->orderBy('min_quantity', 'asc')->get();
                                        $unit = $product->unit ? $product->unit->symbol : '';
                                    @endphp
                                    <div class="price-marquee" data-price-marquee>
                                        <div class="price-marquee-inner">
                                            <span
                                                class="price-chip main">৳{{ number_format($product->discount_price, 0) }}</span>
                                            <span
                                                class="price-chip old">৳{{ number_format($product->base_price, 0) }}</span>

                                            @foreach ($tieredPrices as $price)
                                                <span class="price-chip tier">৳{{ number_format($price->price, 0) }}
                                                    <small>({{ $price->min_quantity }}{{ $price->max_quantity ? ' - ' . $price->max_quantity : '+' }}{{ $unit ? ' ' . $unit : '' }})</small></span>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Footer: Stock & Add to Cart -->
                                    <div class="product-footer">
                                        <span
                                            class="stock-status {{ $product->stock_quantity > 10 ? 'in-stock' : ($product->stock_quantity > 0 ? 'low-stock' : 'out-of-stock') }}">
                                            {{ $product->stock_quantity > 10 ? 'In Stock' : ($product->stock_quantity > 0 ? 'Low Stock' : 'Out') }}
                                        </span>
                                        <button class="add-to-cart-btn" data-product-id="{{ $product->id }}">
                                            <i class="fas fa-cart-plus"></i> Cart
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
                <div class="modal-header border pb-0">
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
                // Initialize all Swiper sliders - store in window object for pause/resume control
                window.sliders = {
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
                        slidesPerView: 1,
                        spaceBetween: 15,
                        navigation: {
                            nextEl: '.swiper-button-next',
                            prevEl: '.swiper-button-prev',
                        },
                        breakpoints: {
                            370: {
                                slidesPerView: 2
                            },
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
                        slidesPerView: 1,
                        spaceBetween: 20,
                        navigation: {
                            nextEl: '.swiper-button-next',
                            prevEl: '.swiper-button-prev',
                        },
                        breakpoints: {
                            576: {
                                slidesPerView: 1
                            },
                            768: {
                                slidesPerView: 2
                            },
                            992: {
                                slidesPerView: 3
                            },
                            1200: {
                                slidesPerView: 4
                            }
                        }
                    }),

                    bestDeals: new Swiper('.best-deals-slider', {
                        slidesPerView: 1,
                        spaceBetween: 20,
                        navigation: {
                            nextEl: '.swiper-button-next',
                            prevEl: '.swiper-button-prev',
                        },
                        breakpoints: {
                            576: {
                                slidesPerView: 1
                            },
                            768: {
                                slidesPerView: 2
                            },
                            992: {
                                slidesPerView: 3
                            },
                            1200: {
                                slidesPerView: 4
                            }
                        }
                    }),

                    brand: new Swiper('.brand-slider', {
                        slidesPerView: 1,
                        spaceBetween: 20,
                        navigation: {
                            nextEl: '.swiper-button-next',
                            prevEl: '.swiper-button-prev',
                        },
                        breakpoints: {
                            576: {
                                slidesPerView: 1
                            },
                            768: {
                                slidesPerView: 2
                            },
                            992: {
                                slidesPerView: 3
                            },
                            1200: {
                                slidesPerView: 4
                            }
                        }
                    })
                };

                // Initialize category product sliders
                document.querySelectorAll('.category-products-slider').forEach(slider => {
                    new Swiper(slider, {
                        slidesPerView: 1,
                        spaceBetween: 20,
                        navigation: {
                            nextEl: slider.querySelector('.swiper-button-next'),
                            prevEl: slider.querySelector('.swiper-button-prev'),
                        },
                        breakpoints: {
                            576: {
                                slidesPerView: 1
                            },
                            768: {
                                slidesPerView: 2
                            },
                            992: {
                                slidesPerView: 3
                            },
                            1200: {
                                slidesPerView: 4
                            }
                        }
                    });
                });

                // Enable marquee only when price line overflows
                const initPriceMarquee = () => {
                    document.querySelectorAll('[data-price-marquee]').forEach(container => {
                        const firstTrack = container.querySelector('.price-marquee-inner');
                        if (!firstTrack) return;

                        const needsMarquee = firstTrack.scrollWidth > container.clientWidth + 2;

                        if (needsMarquee) {
                            container.classList.add('marquee');
                            if (!container.querySelector('.price-marquee-dup')) {
                                const dup = firstTrack.cloneNode(true);
                                dup.classList.add('price-marquee-dup');
                                container.appendChild(dup);
                            }
                        } else {
                            container.classList.remove('marquee');
                            const dup = container.querySelector('.price-marquee-dup');
                            if (dup) dup.remove();
                        }
                    });
                };

                initPriceMarquee();
                window.addEventListener('resize', () => initPriceMarquee());

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

                            // Pause all background sliders when modal opens
                            pauseAllSliders();

                            modal.show();

                            // Initialize gallery slider after modal is shown
                            setTimeout(() => {
                                initGallerySlider();
                                // Auto-play video if exists
                                playVideoInModal();
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
                        videoSlide.className = 'swiper-slide video-slide';
                        videoSlide.innerHTML = `
            <div class="ratio ratio-16x9 h-100">
                <iframe src="${videoUrl}"
                        title="Product Video"
                        allowfullscreen
                        class="w-100 h-100 product-video"
                        data-autoplay="true">
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

                // Auto-play video in modal
                function playVideoInModal() {
                    const activeSlide = document.querySelector('.product-gallery-slider .swiper-slide-active');
                    if (activeSlide) {
                        const video = activeSlide.querySelector('.product-video');
                        if (video) {
                            // For iframes, we add ?autoplay=1 parameter
                            if (video.tagName.toLowerCase() === 'iframe') {
                                const src = video.src;
                                if (!src.includes('?')) {
                                    video.src = src + '?autoplay=1';
                                } else if (!src.includes('autoplay')) {
                                    video.src = src + '&autoplay=1';
                                }
                            } else {
                                video.play();
                            }
                        }
                    }
                }

                // Pause all videos
                function pauseAllVideos() {
                    document.querySelectorAll('#quickViewModal video, #quickViewModal iframe').forEach(elem => {
                        if (elem.tagName.toLowerCase() === 'iframe') {
                            // For iframes, we can't directly pause, but we can handle it differently
                            const src = elem.src;
                            if (src.includes('autoplay=1')) {
                                elem.src = src.replace('?autoplay=1', '').replace('&autoplay=1', '');
                            }
                        } else {
                            elem.pause();
                        }
                    });
                }

                // Pause all background sliders when modal opens
                function pauseAllSliders() {
                    // Pause all registered sliders
                    if (window.sliders) {
                        Object.keys(window.sliders).forEach(key => {
                            if (window.sliders[key] && window.sliders[key].autoplay) {
                                window.sliders[key].autoplay.stop();
                            }
                        });
                    }
                }

                // Resume sliders when modal closes
                function resumeAllSliders() {
                    if (window.sliders) {
                        Object.keys(window.sliders).forEach(key => {
                            if (window.sliders[key] && window.sliders[key].autoplay) {
                                window.sliders[key].autoplay.start();
                            }
                        });
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
                        on: {
                            slideChange: function() {
                                pauseAllVideos();
                                playVideoInModal();
                            }
                        }
                    });
                }

                // Setup modal close event to resume sliders
                const quickViewModalElement = document.getElementById('quickViewModal');
                if (quickViewModalElement) {
                    quickViewModalElement.addEventListener('hidden.bs.modal', function() {
                        resumeAllSliders();
                        pauseAllVideos();
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

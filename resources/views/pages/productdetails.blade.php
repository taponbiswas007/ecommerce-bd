@extends('layouts.app')

@section('title', $product->name . ' - ElectroHub')

@section('styles')
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6f42c1;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
        }

        /* Product Gallery */
        .product-gallery {
            position: sticky;
            top: 20px;
        }

        .main-gallery {
            height: 400px;
            border-radius: 15px;
            overflow: hidden;
            margin-bottom: 10px;
            background: #f8f9fa;
        }

        .main-gallery img,
        .main-gallery video {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .thumbnail-gallery {
            height: 100px;
        }

        .thumbnail-gallery .swiper-slide {
            opacity: 0.6;
            cursor: pointer;
            border: 2px solid transparent;
            border-radius: 8px;
            overflow: hidden;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .thumbnail-gallery .swiper-slide-thumb-active {
            opacity: 1;
            border-color: var(--primary-color);
        }

        .thumbnail-gallery img,
        .thumbnail-gallery .video-thumb {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .video-thumb {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #212529;
        }

        .video-thumb::after {
            content: '\f144';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            color: white;
            font-size: 24px;
            position: absolute;
        }

        /* Product Details */
        .product-title {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
            color: #212529;
        }

        .product-meta {
            margin-bottom: 20px;
        }

        .product-sku {
            color: #6c757d;
            font-size: 14px;
        }

        .product-brand {
            color: var(--primary-color);
            font-weight: 500;
        }

        .rating-container {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .rating-stars {
            color: var(--warning-color);
        }

        .rating-count {
            color: #6c757d;
            font-size: 14px;
        }

        .write-review {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 14px;
        }

        .write-review:hover {
            text-decoration: underline;
        }

        /* Price Section */
        .price-section {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
            border: 1px solid #dee2e6;
        }

        .current-price {
            font-size: 32px;
            font-weight: 700;
            color: var(--danger-color);
            line-height: 1;
        }

        .old-price {
            font-size: 20px;
            color: #6c757d;
            text-decoration: line-through;
            margin-left: 10px;
        }

        .discount-badge {
            background: var(--danger-color);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            margin-left: 10px;
        }

        .tax-info {
            font-size: 12px;
            color: #6c757d;
            margin-top: 5px;
        }

        /* Stock & Shipping */
        .stock-status {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }

        .in-stock {
            color: #198754;
            font-weight: 600;
        }

        .low-stock {
            color: var(--warning-color);
            font-weight: 600;
        }

        .out-of-stock {
            color: var(--danger-color);
            font-weight: 600;
        }

        .shipping-info {
            background: #e7f1ff;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 25px;
            border-left: 4px solid var(--primary-color);
        }

        .shipping-info i {
            color: var(--primary-color);
            margin-right: 10px;
        }

        /* Quantity & Add to Cart */
        .quantity-selector {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
        }

        .quantity-input-group {
            display: flex;
            align-items: center;
            border: 2px solid #dee2e6;
            border-radius: 10px;
            overflow: hidden;
            width: 140px;
        }

        .quantity-btn {
            background: #f8f9fa;
            border: none;
            width: 40px;
            height: 45px;
            font-size: 18px;
            font-weight: bold;
            color: #495057;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .quantity-btn:hover {
            background: #e9ecef;
        }

        .quantity-input {
            width: 60px;
            height: 45px;
            border: none;
            text-align: center;
            font-size: 16px;
            font-weight: 600;
            background: white;
        }

        .quantity-input:focus {
            outline: none;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
        }

        .btn-add-to-cart {
            flex: 1;
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 15px 25px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-add-to-cart:hover {
            background: #0b5ed7;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
        }

        .btn-buy-now {
            flex: 1;
            background: var(--danger-color);
            color: white;
            border: none;
            padding: 15px 25px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-buy-now:hover {
            background: #bb2d3b;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
        }

        /* Wishlist & Compare */
        .wishlist-compare {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
        }

        .wishlist-btn,
        .compare-btn {
            flex: 1;
            background: white;
            border: 2px solid #dee2e6;
            color: #495057;
            padding: 12px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
        }

        .wishlist-btn:hover,
        .compare-btn:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        .wishlist-btn.active {
            border-color: var(--danger-color);
            color: var(--danger-color);
            background: #fff5f5;
        }

        /* Product Tabs */
        .product-tabs {
            margin-top: 50px;
        }

        .nav-tabs-custom {
            border-bottom: 2px solid #dee2e6;
        }

        .nav-tabs-custom .nav-link {
            border: none;
            color: #6c757d;
            font-weight: 500;
            padding: 15px 25px;
            margin-right: 5px;
            border-radius: 10px 10px 0 0;
            position: relative;
        }

        .nav-tabs-custom .nav-link.active {
            color: var(--primary-color);
            background: white;
            border: 2px solid #dee2e6;
            border-bottom: none;
            margin-bottom: -2px;
        }

        .nav-tabs-custom .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 2px;
            background: white;
        }

        .tab-content {
            padding: 30px;
            border: 2px solid #dee2e6;
            border-top: none;
            border-radius: 0 0 15px 15px;
            background: white;
        }

        .specifications-table {
            width: 100%;
        }

        .specifications-table tr {
            border-bottom: 1px solid #dee2e6;
        }

        .specifications-table td {
            padding: 12px 15px;
        }

        .specifications-table td:first-child {
            font-weight: 600;
            color: #495057;
            width: 200px;
            background: #f8f9fa;
        }

        /* Reviews */
        .review-item {
            border-bottom: 1px solid #dee2e6;
            padding: 20px 0;
        }

        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .review-author {
            font-weight: 600;
            color: #212529;
        }

        .review-date {
            color: #6c757d;
            font-size: 14px;
        }

        /* Related Products */
        .related-products-section {
            margin-top: 60px;
            padding-top: 40px;
            border-top: 2px solid #dee2e6;
        }

        .section-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 30px;
            color: #212529;
            position: relative;
            padding-bottom: 10px;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background: var(--primary-color);
            border-radius: 2px;
        }

        .product-gallery .swiper-button-next,
        .product-gallery .swiper-button-prev {
            background: rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
            width: 50px;
            height: 50px;
            border-radius: 50%;
            color: #000000;
            border: 2px solid #000000;
            transition: all 0.3s ease;
        }

        .product-gallery .swiper-button-next:hover,
        .product-gallery .swiper-button-prev:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .product-gallery .swiper-button-next:after,
        .product-gallery .swiper-button-prev:after {
            font-size: 20px;
            font-weight: bold;
        }

        /* Product Attributes */
        .product-attributes {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            border: 1px solid #dee2e6;
            margin-bottom: 25px;
        }

        .attribute-group {
            margin-bottom: 20px;
        }

        .attribute-group:last-child {
            margin-bottom: 0;
        }

        .attribute-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 10px;
            display: block;
        }

        .attribute-options {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .attribute-option {
            min-width: 80px;
            padding: 8px 16px;
            font-size: 14px;
            font-weight: 500;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            background: white;
            transition: all 0.3s ease;
            cursor: pointer;
            text-align: center;
        }

        .attribute-option:hover {
            border-color: var(--primary-color);
            transform: translateY(-2px);
        }

        .attribute-option.selected {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .attribute-single-value {
            padding: 8px 16px;
            background: #e9ecef;
            border-radius: 8px;
            font-weight: 500;
            color: #495057;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .main-gallery {
                height: 300px;
            }

            .thumbnail-gallery {
                height: 80px;
            }

            .product-title {
                font-size: 24px;
            }

            .current-price {
                font-size: 28px;
            }

            .action-buttons {
                flex-direction: column;
            }

            .quantity-selector {
                flex-direction: column;
                align-items: flex-start;
            }

            .tab-content {
                padding: 20px 15px;
            }
        }

        @media (max-width: 576px) {
            .main-gallery {
                height: 250px;
            }

            .thumbnail-gallery {
                height: 60px;
            }

            .product-title {
                font-size: 20px;
            }

            .wishlist-compare {
                flex-direction: column;
            }
        }

        /* Social Share */
        .social-share {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 20px;
        }

        .share-label {
            font-weight: 500;
            color: #495057;
        }

        .social-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: transform 0.3s ease;
        }

        .social-icon:hover {
            transform: translateY(-3px);
        }

        .facebook {
            background: #3b5998;
        }

        .twitter {
            background: #1da1f2;
        }

        .pinterest {
            background: #bd081c;
        }

        .whatsapp {
            background: #25d366;
        }

        .linkedin {
            background: #0077b5;
        }

        /* Payment Methods */
        .payment-methods {
            margin-top: 20px;
        }

        .payment-icons {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .payment-icon {
            width: 40px;
            height: 25px;
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            color: #495057;
        }

        /* Attribute Selection Warning */
        .attribute-warning {
            display: none;
            color: var(--danger-color);
            font-size: 14px;
            margin-top: 10px;
        }

        .attribute-warning.show {
            display: block;
        }
    </style>

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
@endsection

@section('content')
    <div class="container py-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('shop') }}">Shop</a></li>
                @if ($product->category)
                    <li class="breadcrumb-item">
                        <a href="{{ route('category.show', $product->category->slug) }}">
                            {{ $product->category->name }}
                        </a>
                    </li>
                @endif
                <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
            </ol>
        </nav>

        <div class="row">
            <!-- Left Column: Product Images & Video -->
            <div class="col-lg-6 mb-4">
                <div class="product-gallery">
                    <!-- Main Image/Video Slider -->
                    <div class="swiper main-gallery">
                        <div class="swiper-wrapper">
                            @if ($product->images && $product->images->count() > 0)
                                @foreach ($product->images as $image)
                                    <div class="swiper-slide">
                                        <img src="{{ asset('storage/' . $image->image_path) }}"
                                            alt="{{ $image->alt_text ?? $product->name }}" class="img-fluid"
                                            data-full="{{ asset('storage/' . $image->image_path) }}">
                                    </div>
                                @endforeach
                            @else
                                <div class="swiper-slide">
                                    <img src="https://via.placeholder.com/600x400?text=No+Image" alt="{{ $product->name }}"
                                        class="img-fluid">
                                </div>
                            @endif

                            @if ($product->video_url)
                                <div class="swiper-slide">
                                    <div class="ratio ratio-16x9 h-100">
                                        <iframe src="{{ $product->video_url }}" title="{{ $product->name }} video"
                                            allowfullscreen class="w-100 h-100">
                                        </iframe>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>

                    <!-- Thumbnail Gallery -->
                    <div class="swiper thumbnail-gallery">
                        <div class="swiper-wrapper">
                            @if ($product->images && $product->images->count() > 0)
                                @foreach ($product->images as $image)
                                    <div class="swiper-slide">
                                        <img src="{{ asset('storage/' . $image->image_path) }}"
                                            alt="{{ $image->alt_text ?? $product->name }}" class="img-fluid">
                                    </div>
                                @endforeach
                            @endif

                            @if ($product->video_url)
                                <div class="swiper-slide">
                                    <div class="video-thumb">
                                        <i class="fas fa-play-circle fa-2x text-white"></i>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Share & Zoom -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="social-share">
                            <span class="share-label">Share:</span>
                            <a href="#" class="social-icon facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="social-icon twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="social-icon whatsapp">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        </div>
                        <button class="btn btn-outline-secondary btn-sm" onclick="zoomImage()">
                            <i class="fas fa-search-plus me-1"></i> Zoom
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right Column: Product Details -->
            <div class="col-lg-6">
                <!-- Product Title & Meta -->
                <h1 class="product-title">{{ $product->name }}</h1>

                <div class="product-meta">
                    <span class="product-sku">
                        SKU: <strong>{{ $product->id }}</strong>
                    </span>
                    <span class="ms-3 product-brand">
                        @if ($product->category)
                            Category: <a href="{{ route('category.show', $product->category->slug) }}"
                                class="text-decoration-none">
                                {{ $product->category->name }}
                            </a>
                        @endif
                    </span>
                </div>

                <!-- Rating -->
                <div class="rating-container">
                    <div class="rating-stars">
                        @php
                            $averageRating = $product->average_rating ?? 0;
                            $fullStars = floor($averageRating);
                            $hasHalfStar = $averageRating - $fullStars >= 0.5;
                        @endphp

                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $fullStars)
                                <i class="fas fa-star"></i>
                            @elseif($i == $fullStars + 1 && $hasHalfStar)
                                <i class="fas fa-star-half-alt"></i>
                            @else
                                <i class="far fa-star"></i>
                            @endif
                        @endfor
                    </div>
                    <span class="rating-count">({{ $product->total_reviews }} reviews)</span>
                    <a href="#reviews" class="write-review">Write a review</a>
                </div>

                <!-- Price Section -->
                <div class="price-section">
                    <div class="d-flex align-items-center flex-wrap">
                        <span class="current-price">
                            ৳{{ number_format($product->discount_price ?? $product->base_price, 2) }}
                        </span>

                        @if ($product->has_discount)
                            <span class="old-price">
                                ৳{{ number_format($product->base_price, 2) }}
                            </span>
                            <span class="discount-badge">
                                Save {{ $product->discount_percentage }}%
                            </span>
                        @endif
                    </div>
                    <!-- Tax Information -->
                    @php
                        use App\Services\TaxCalculator;
                        $priceToCalculate = $product->discount_price ?? $product->base_price;
                        $taxBreakdown = TaxCalculator::getPriceBreakdown($product, $priceToCalculate);
                    @endphp
                    <div class="tax-info">
                        @if ($taxBreakdown['vat_percentage'] > 0)
                            <small class="d-block">
                                <i class="fas fa-check-circle text-success me-1"></i>
                                VAT ({{ $taxBreakdown['vat_percentage'] }}%)
                                @if ($taxBreakdown['vat_included'])
                                    <span class="text-muted">included in price</span>
                                @else
                                    <span class="text-danger">will be added</span>
                                @endif
                            </small>
                        @endif
                        @if ($taxBreakdown['ait_percentage'] > 0)
                            <small class="d-block">
                                <i class="fas fa-check-circle text-success me-1"></i>
                                AIT ({{ $taxBreakdown['ait_percentage'] }}%)
                                @if ($taxBreakdown['ait_included'])
                                    <span class="text-muted">included in price</span>
                                @else
                                    <span class="text-danger">will be added</span>
                                @endif
                            </small>
                        @endif
                    </div>
                </div>

                <!-- Short Description -->
                <div class="mb-4">
                    <p>{{ $product->short_description }}</p>
                </div>

                <!-- Stock Status -->
                <div class="stock-status">
                    <span class="fw-bold">Availability:</span>
                    @if ($product->stock_quantity > 10)
                        <span class="in-stock">
                            <i class="fas fa-check-circle me-1"></i> In Stock
                        </span>
                    @elseif($product->stock_quantity > 0)
                        <span class="low-stock">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            Low Stock ({{ $product->stock_quantity }} left)
                        </span>
                    @else
                        <span class="out-of-stock">
                            <i class="fas fa-times-circle me-1"></i> Out of Stock
                        </span>
                    @endif
                </div>

                <!-- Shipping Info -->
                <div class="shipping-info">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-shipping-fast fa-lg"></i>
                        <div>
                            <strong>Shipping Charges Apply</strong>
                            <p class="mb-0 small">Shipping cost will be calculated at checkout</p>
                        </div>
                    </div>
                </div>

                <!-- Product Attributes Selection -->
                @php
                    $attributes = $product->attribute_pairs ?? [];
                    $hasMultipleOptions = false;

                    foreach ($attributes as $key => $value) {
                        $options = array_map('trim', explode(',', $value));
                        if (count($options) > 1) {
                            $hasMultipleOptions = true;
                            break;
                        }
                    }
                @endphp

                @if (!empty($attributes))
                    <div class="product-attributes">
                        @foreach ($attributes as $key => $value)
                            @php
                                $options = array_map('trim', explode(',', $value));
                            @endphp
                            <div class="attribute-group" data-attribute-key="{{ $key }}">
                                <label class="attribute-label">{{ ucfirst(str_replace('_', ' ', $key)) }}:</label>

                                @if (count($options) > 1)
                                    <div class="attribute-options">
                                        @foreach ($options as $index => $option)
                                            <button type="button"
                                                class="attribute-option @if ($index === 0) selected @endif"
                                                data-attribute="{{ $key }}" data-value="{{ $option }}"
                                                onclick="selectAttribute(this)">
                                                {{ $option }}
                                            </button>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="attribute-single-value">
                                        {{ $value }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                        <div class="attribute-warning" id="attributeWarning">
                            <i class="fas fa-exclamation-circle me-1"></i>
                            Please select all required attributes before adding to cart.
                        </div>
                        <input type="hidden" id="selectedAttributes" name="selectedAttributes" value="">
                    </div>
                @endif

                <!-- Quantity Selector -->
                <div class="quantity-selector">
                    <label class="fw-bold">Quantity:</label>
                    <div class="quantity-input-group">
                        <button class="quantity-btn" type="button" onclick="decreaseQuantity()">-</button>
                        <input type="number" id="quantity" class="quantity-input"
                            value="{{ $product->min_order_quantity }}" min="{{ $product->min_order_quantity }}"
                            max="{{ $product->stock_quantity }}">
                        <button class="quantity-btn" type="button" onclick="increaseQuantity()">+</button>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    @if ($product->stock_quantity > 0)
                        <button class="btn-add-to-cart" onclick="handleAddToCart('{{ $product->hashid }}')">
                            <i class="fas fa-cart-plus"></i> Add to Cart
                        </button>
                        <button class="btn-buy-now" onclick="handleBuyNow('{{ $product->hashid }}')">
                            <i class="fas fa-bolt"></i> Buy Now
                        </button>
                    @else
                        <button class="btn-add-to-cart" disabled>
                            <i class="fas fa-cart-plus"></i> Out of Stock
                        </button>
                        <button class="btn-buy-now" disabled>
                            <i class="fas fa-bolt"></i> Notify Me
                        </button>
                    @endif
                </div>

                <!-- Wishlist & Compare -->
                <div class="wishlist-compare">
                    <button class="wishlist-btn {{ Auth::check() && $product->isInWishlist() ? 'active' : '' }}"
                        onclick="toggleWishlist('{{ $product->hashid }}', this)">
                        <i class="fas fa-heart"></i>
                        <span id="wishlist-text">
                            {{ Auth::check() && $product->isInWishlist() ? 'In Wishlist' : 'Add to Wishlist' }}
                        </span>
                    </button>
                </div>

                <!-- Payment Methods -->
                <div class="payment-methods">
                    <p class="mb-2"><strong>Payment Methods:</strong></p>
                    <div class="payment-icons">
                        <div class="payment-icon">COD</div>
                        <div class="payment-icon">
                            <i class="fab fa-cc-visa"></i>
                        </div>
                        <div class="payment-icon">
                            <i class="fab fa-cc-mastercard"></i>
                        </div>
                        <div class="payment-icon">
                            <i class="fab fa-cc-paypal"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Tabs -->
        <div class="product-tabs">
            <ul class="nav nav-tabs nav-tabs-custom" id="productTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="description-tab" data-bs-toggle="tab"
                        data-bs-target="#description" type="button">
                        <i class="fas fa-file-alt me-2"></i> Description
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="specifications-tab" data-bs-toggle="tab"
                        data-bs-target="#specifications" type="button">
                        <i class="fas fa-list-alt me-2"></i> Specifications
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews"
                        type="button">
                        <i class="fas fa-star me-2"></i> Reviews ({{ $product->total_reviews }})
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="productTabContent">
                <!-- Description Tab -->
                <div class="tab-pane fade show active" id="description" role="tabpanel">
                    <div class="product-description">
                        {!! $product->full_description !!}

                        @php
                            $attributes = $product->attribute_pairs ?? [];
                        @endphp
                        @if (!empty($attributes))
                            <div class="mt-4">
                                <h5>Additional Information</h5>
                                <div class="row mt-3">
                                    @foreach ($attributes as $key => $value)
                                        <div class="col-md-6 mb-2">
                                            <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong>
                                            <span>{{ $value }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Specifications Tab -->
                <div class="tab-pane fade" id="specifications" role="tabpanel">
                    <table class="specifications-table">
                        <tbody>
                            <tr>
                                <td>Product Name</td>
                                <td>{{ $product->name }}</td>
                            </tr>
                            @if ($product->category)
                                <tr>
                                    <td>Category</td>
                                    <td>{{ $product->category->name }}</td>
                                </tr>
                            @endif
                            @if ($product->unit)
                                <tr>
                                    <td>Unit</td>
                                    <td>{{ $product->unit->name }}</td>
                                </tr>
                            @endif
                            @if ($product->weight)
                                <tr>
                                    <td>Weight</td>
                                    <td>{{ $product->weight }} kg</td>
                                </tr>
                            @endif
                            @if ($product->dimensions)
                                <tr>
                                    <td>Dimensions</td>
                                    <td>{{ $product->dimensions }}</td>
                                </tr>
                            @endif
                            <tr>
                                <td>Minimum Order</td>
                                <td>{{ $product->min_order_quantity }}</td>
                            </tr>
                            <tr>
                                <td>Stock Quantity</td>
                                <td>{{ $product->stock_quantity }}</td>
                            </tr>
                            @php
                                $attributes = $product->attribute_pairs ?? [];
                            @endphp
                            @if (!empty($attributes))
                                @foreach ($attributes as $key => $value)
                                    <tr>
                                        <td>{{ ucfirst(str_replace('_', ' ', $key)) }}</td>
                                        <td>{{ $value }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

                <!-- Reviews Tab -->
                <div class="tab-pane fade" id="reviews" role="tabpanel">
                    @if ($product->total_reviews > 0)
                        <div class="rating-summary mb-4">
                            <div class="row align-items-center">
                                <div class="col-md-4 text-center">
                                    <h2 class="mb-0">{{ number_format($product->average_rating, 1) }}</h2>
                                    <div class="rating-stars mb-2">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= floor($product->average_rating))
                                                <i class="fas fa-star"></i>
                                            @elseif($i == ceil($product->average_rating) && fmod($product->average_rating, 1) >= 0.5)
                                                <i class="fas fa-star-half-alt"></i>
                                            @else
                                                <i class="far fa-star"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <p class="text-muted">{{ $product->total_reviews }} reviews</p>
                                </div>
                                <div class="col-md-8">
                                    <!-- Rating bars would go here -->
                                </div>
                            </div>
                        </div>

                        <!-- Reviews List -->
                        <div class="reviews-list">
                            <!-- Sample review - replace with actual reviews from database -->
                            <div class="review-item">
                                <div class="review-header">
                                    <div>
                                        <span class="review-author">John Doe</span>
                                        <div class="rating-stars d-inline-block ms-2">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="far fa-star"></i>
                                        </div>
                                    </div>
                                    <span class="review-date">2 days ago</span>
                                </div>
                                <p class="review-text">Excellent product! Works perfectly as described.</p>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                            <h4>No reviews yet</h4>
                            <p class="text-muted">Be the first to review this product</p>
                            <button class="btn btn-primary" onclick="openReviewModal()">
                                <i class="fas fa-edit me-2"></i> Write a Review
                            </button>
                        </div>
                    @endif

                    <!-- Write Review Button -->
                    <div class="text-center mt-4">
                        <button class="btn btn-outline-primary" onclick="openReviewModal()">
                            <i class="fas fa-edit me-2"></i> Write a Review
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if ($relatedProducts && $relatedProducts->count() > 0)
            <div class="related-products-section">
                <h3 class="section-title">Related Products</h3>
                <div class="row">
                    @foreach ($relatedProducts as $relatedProduct)
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="card product-card h-100 border shadow-sm">
                                <a href="{{ route('product.show', $relatedProduct->slug) }}"
                                    class="text-decoration-none">
                                    <div class="position-relative overflow-hidden">
                                        @php
                                            $image = $relatedProduct->primaryImage ?? $relatedProduct->images->first();
                                        @endphp
                                        <img src="{{ $image ? asset('storage/' . $image->image_path) : 'https://via.placeholder.com/300x200' }}"
                                            class="card-img-top product-img" alt="{{ $relatedProduct->name }}"
                                            style="height: 200px; object-fit: contain; padding: 20px;">

                                        @if ($relatedProduct->has_discount)
                                            <span class="position-absolute top-0 start-0 m-2 badge bg-danger">
                                                -{{ $relatedProduct->discount_percentage }}%
                                            </span>
                                        @endif
                                    </div>
                                    <div class="card-body">
                                        <h6 class="card-title fw-bold mb-2 text-dark"
                                            style="height: 42px; overflow: hidden;">
                                            {{ Str::limit($relatedProduct->name, 40) }}
                                        </h6>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                @if ($relatedProduct->has_discount)
                                                    <span class="fw-bold text-danger">
                                                        ৳{{ number_format($relatedProduct->discount_price, 2) }}
                                                    </span>
                                                    <del class="text-muted small ms-1">
                                                        ৳{{ number_format($relatedProduct->base_price, 2) }}
                                                    </del>
                                                @else
                                                    <span class="fw-bold text-dark">
                                                        ৳{{ number_format($relatedProduct->base_price, 2) }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <div class="card-footer bg-transparent border-0 text-end">
                                    <button
                                        class="wishlist-btn {{ Auth::check() && $relatedProduct->isInWishlist() ? 'active' : '' }}"
                                        onclick="toggleWishlist('{{ $relatedProduct->hashid }}', this)">
                                        <i class="fas fa-heart"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Zoom Modal -->
    <div class="modal fade" id="zoomModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header border">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="zoomImage" src="" alt="" class="img-fluid">
                </div>
            </div>
        </div>
    </div>

    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Login Required</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p class="mb-3">You must be logged in to add products to your cart.</p>
                    <a href="{{ route('login') }}" class="btn btn-primary w-100">Login</a>
                    <div class="mt-2">
                        <span>Don't have an account?</span>
                        <a href="{{ route('register') }}">Register</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Review Modal -->
    <div class="modal fade" id="reviewModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Write a Review</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="reviewForm">
                        <div class="mb-3">
                            <label class="form-label">Rating</label>
                            <div class="rating-input">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="far fa-star fa-lg me-1" data-rating="{{ $i }}"
                                        onclick="setRating({{ $i }})"></i>
                                @endfor
                            </div>
                            <input type="hidden" id="rating" name="rating" value="0">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Review Title</label>
                            <input type="text" class="form-control" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Your Review</label>
                            <textarea class="form-control" name="review" rows="4" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Submit Review</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize main gallery
            const mainGallery = new Swiper('.main-gallery', {
                spaceBetween: 10,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
            });

            // Initialize thumbnail gallery
            const thumbnailGallery = new Swiper('.thumbnail-gallery', {
                spaceBetween: 10,
                slidesPerView: 4,
                freeMode: true,
                watchSlidesProgress: true,
            });

            // Connect galleries
            mainGallery.controller.control = thumbnailGallery;
            thumbnailGallery.controller.control = mainGallery;

            // Click on thumbnail to switch slide
            document.querySelectorAll('.thumbnail-gallery .swiper-slide').forEach((thumb, index) => {
                thumb.addEventListener('click', function() {
                    mainGallery.slideTo(index);
                });
            });

            // Initialize all attribute options on page load
            initializeAttributes();
        });

        // Initialize attribute selection
        function initializeAttributes() {
            // Select the first option for each attribute by default
            document.querySelectorAll('.attribute-options').forEach(optionsContainer => {
                const firstOption = optionsContainer.querySelector('.attribute-option');
                if (firstOption) {
                    selectAttribute(firstOption);
                }
            });
            updateSelectedAttributes();
        }

        // Quantity functions
        function decreaseQuantity() {
            const input = document.getElementById('quantity');
            const min = parseInt(input.min);
            if (parseInt(input.value) > min) {
                input.value = parseInt(input.value) - 1;
            }
        }

        function increaseQuantity() {
            const input = document.getElementById('quantity');
            const max = parseInt(input.max);
            if (parseInt(input.value) < max) {
                input.value = parseInt(input.value) + 1;
            }
        }

        // Attribute selection
        function selectAttribute(button) {
            const attributeGroup = button.closest('.attribute-group');
            const siblings = attributeGroup.querySelectorAll('.attribute-option');

            // Remove selected class from all siblings
            siblings.forEach(btn => btn.classList.remove('selected'));

            // Add selected class to clicked button
            button.classList.add('selected');

            // Update hidden input
            updateSelectedAttributes();

            // Hide warning if shown
            const warning = document.getElementById('attributeWarning');
            if (warning) {
                warning.classList.remove('show');
            }
        }

        function updateSelectedAttributes() {
            const selectedAttrs = {};
            document.querySelectorAll('.attribute-group').forEach(group => {
                const key = group.getAttribute('data-attribute-key');
                const selectedOption = group.querySelector('.attribute-option.selected');
                if (selectedOption) {
                    selectedAttrs[key] = selectedOption.getAttribute('data-value');
                } else {
                    // If no option, check for single-value attribute
                    const singleValueElement = group.querySelector('.attribute-single-value');
                    if (singleValueElement) {
                        selectedAttrs[key] = singleValueElement.textContent.trim();
                    }
                }
            });
            document.getElementById('selectedAttributes').value = JSON.stringify(selectedAttrs);
            console.log('Selected attributes:', selectedAttrs); // For debugging
        }

        // Validate all required attributes are selected
        function validateAttributes() {
            const attributeGroups = document.querySelectorAll('.attribute-group');
            let allSelected = true;

            attributeGroups.forEach(group => {
                const hasOptions = group.querySelector('.attribute-options');
                if (hasOptions) {
                    const hasSelected = group.querySelector('.attribute-option.selected');
                    if (!hasSelected) {
                        allSelected = false;
                    }
                }
            });

            return allSelected;
        }

        // Add to cart handler
        function handleAddToCart(productId) {
            // First validate attributes if product has multiple options
            const hasMultipleOptions = @json($hasMultipleOptions);

            if (hasMultipleOptions && !validateAttributes()) {
                const warning = document.getElementById('attributeWarning');
                if (warning) {
                    warning.classList.add('show');
                }
                Toast.fire({
                    icon: 'warning',
                    title: 'Please select all required attributes'
                });
                return;
            }

            // Get selected attributes
            updateSelectedAttributes();
            const quantity = document.getElementById('quantity').value;
            let attributes = {};
            const attrsInput = document.getElementById('selectedAttributes');

            if (attrsInput && attrsInput.value && attrsInput.value.trim().startsWith('{')) {
                try {
                    attributes = JSON.parse(attrsInput.value);
                } catch (e) {
                    console.error('Error parsing attributes:', e);
                    attributes = {};
                }
            } else {
                attributes = {};
            }

            // Call global addToCart function with attributes
            addToCart(productId, quantity, attributes);
        }

        // Buy now handler
        function handleBuyNow(productId) {
            // First validate attributes if product has multiple options
            const hasMultipleOptions = @json($hasMultipleOptions);

            if (hasMultipleOptions && !validateAttributes()) {
                const warning = document.getElementById('attributeWarning');
                if (warning) {
                    warning.classList.add('show');
                }
                Toast.fire({
                    icon: 'warning',
                    title: 'Please select all required attributes'
                });
                return;
            }

            // Get selected attributes
            updateSelectedAttributes();
            const quantity = document.getElementById('quantity').value;
            let attributes = {};
            const attrsInput = document.getElementById('selectedAttributes');

            if (attrsInput && attrsInput.value) {
                try {
                    attributes = JSON.parse(attrsInput.value);
                } catch (e) {
                    console.error('Error parsing attributes:', e);
                }
            }

            // Add to cart and redirect to checkout
            addToCartAndCheckout(productId, quantity, attributes);
        }

        // Function to add to cart and redirect to checkout
        function addToCartAndCheckout(productId, quantity, attributes) {
            @auth
            fetch('{{ route('cart.add') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: quantity,
                        attributes: attributes
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Redirect to checkout after successful addition
                        window.location.href = '{{ route('checkout.index') }}';
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: data.message || 'Failed to add to cart'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Toast.fire({
                        icon: 'error',
                        title: 'An error occurred. Please try again.'
                    });
                });
        @else
            const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
            loginModal.show();
            Toast.fire({
                icon: 'warning',
                title: 'Please login to buy products'
            });
        @endauth
        }

        // Global addToCart function (make sure this exists in your global scripts)
        function addToCart(productId, quantity = 1, attributes = {}) {
            @auth
            fetch('{{ route('cart.add') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: quantity,
                        attributes: attributes
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Toast.fire({
                            icon: 'success',
                            title: 'Product added to cart!'
                        });
                        // Update cart count if needed
                        updateCartCount(data.cart_count);
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: data.message || 'Failed to add to cart'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Toast.fire({
                        icon: 'error',
                        title: 'An error occurred. Please try again.'
                    });
                });
        @else
            const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
            loginModal.show();
            Toast.fire({
                icon: 'warning',
                title: 'Please login to add products to cart'
            });
        @endauth
        }

        // Wishlist function
        function toggleWishlist(productId, button) {
            @auth
            fetch('/wishlist/toggle/' + productId, {
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
                        const text = button.querySelector('#wishlist-text');
                        if (text) {
                            text.textContent = button.classList.contains('active') ? 'In Wishlist' : 'Add to Wishlist';
                        }
                        Toast.fire({
                            icon: 'success',
                            title: data.message
                        });
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: data.message || 'Failed to update wishlist.'
                        });
                    }
                })
                .catch(() => {
                    Toast.fire({
                        icon: 'error',
                        title: 'Error updating wishlist.'
                    });
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

        // Zoom image
        function zoomImage() {
            const activeSlide = document.querySelector('.main-gallery .swiper-slide-active img');
            if (activeSlide) {
                const fullImage = activeSlide.getAttribute('data-full') || activeSlide.src;
                document.getElementById('zoomImage').src = fullImage;
                const modal = new bootstrap.Modal(document.getElementById('zoomModal'));
                modal.show();
            }
        }

        // Review functions
        function openReviewModal() {
            @auth
            const modal = new bootstrap.Modal(document.getElementById('reviewModal'));
            modal.show();
        @else
            const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
            loginModal.show();
            Toast.fire({
                icon: 'warning',
                title: 'Please login to write a review'
            });
        @endauth
        }

        function setRating(rating) {
            const stars = document.querySelectorAll('.rating-input i');
            stars.forEach((star, index) => {
                if (index < rating) {
                    star.classList.remove('far');
                    star.classList.add('fas');
                } else {
                    star.classList.remove('fas');
                    star.classList.add('far');
                }
            });
            document.getElementById('rating').value = rating;
        }

        // Review form submission
        document.getElementById('reviewForm')?.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            formData.append('product_id', {{ $product->id }});

            fetch('{{ route('reviews.store') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Toast.fire({
                            icon: 'success',
                            title: 'Review submitted successfully!'
                        });
                        const modal = bootstrap.Modal.getInstance(document.getElementById('reviewModal'));
                        modal.hide();
                        location.reload();
                    }
                });
        });

        // Toast notification
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

        // Update cart count (global function)
        function updateCartCount(count) {
            const cartCountElement = document.querySelector('.cart-count');
            if (cartCountElement) {
                cartCountElement.textContent = count;
                cartCountElement.style.display = count > 0 ? 'flex' : 'none';
            }
        }

        // Event listener for attribute options
        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('attribute-option')) {
                selectAttribute(e.target);
            }
        });
    </script>
@endsection

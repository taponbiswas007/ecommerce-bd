@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <!-- Breadcrumb & Header -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">{{ $category->name }}</li>
            </ol>
        </nav>

        <!-- Category Header -->
        <div class="row mb-4">
            <div class="col-md-8">
                <div>
                    <h1 class="h2 mb-2">{{ $category->name }}</h1>
                    @if ($category->description)
                        <p class="text-muted">{{ $category->description }}</p>
                    @endif
                </div>
            </div>
            <div class="col-md-4 text-end">
                <div class="d-flex justify-content-end gap-2">
                    <div class="input-group input-group-sm" style="max-width: 250px;">
                        <input type="text" class="form-control" id="searchInput" placeholder="Search products..."
                            value="{{ request('search') }}">
                        <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Sidebar Filters -->
            <div class="col-lg-3 mb-4">
                <div class="card border shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="fas fa-filter"></i> Filters
                        </h5>

                        <!-- Price Range Filter -->
                        <div class="filter-section mb-4">
                            <h6 class="text-uppercase small fw-bold mb-3">
                                <a href="#priceCollapse" data-bs-toggle="collapse" class="text-dark text-decoration-none">
                                    <i class="fas fa-chevron-down"></i> Price Range
                                </a>
                            </h6>
                            <div class="collapse show" id="priceCollapse">
                                <div class="mb-3">
                                    <input type="range" class="form-range" id="priceRange" min="0" max="100000"
                                        value="{{ request('max_price', '100000') }}" step="1000">
                                    <div class="d-flex justify-content-between mt-2">
                                        <small>৳ <span id="minPriceDisplay">0</span></small>
                                        <small>৳ <span
                                                id="maxPriceDisplay">{{ request('max_price', '100000') }}</span></small>
                                    </div>
                                </div>
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <input type="number" class="form-control form-control-sm" id="minPriceInput"
                                            placeholder="Min" value="{{ request('min_price', '') }}">
                                    </div>
                                    <div class="col-6">
                                        <input type="number" class="form-control form-control-sm" id="maxPriceInput"
                                            placeholder="Max" value="{{ request('max_price', '') }}">
                                    </div>
                                </div>
                                <button class="btn btn-sm btn-primary w-100" id="applyPriceBtn">Apply</button>
                            </div>
                        </div>

                        <hr class="my-3">

                        <!-- Sort Options -->
                        <div class="filter-section mb-4">
                            <h6 class="text-uppercase small fw-bold mb-3">
                                <a href="#sortCollapse" data-bs-toggle="collapse" class="text-dark text-decoration-none">
                                    <i class="fas fa-chevron-down"></i> Sort By
                                </a>
                            </h6>
                            <div class="collapse show" id="sortCollapse">
                                <select class="form-select form-select-sm" id="sortBy">
                                    <option value="newest" {{ request('sort_by') == 'newest' ? 'selected' : '' }}>Newest
                                    </option>
                                    <option value="price_low" {{ request('sort_by') == 'price_low' ? 'selected' : '' }}>
                                        Price: Low to High</option>
                                    <option value="price_high" {{ request('sort_by') == 'price_high' ? 'selected' : '' }}>
                                        Price: High to Low</option>
                                    <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Name: A to
                                        Z</option>
                                    <option value="popular" {{ request('sort_by') == 'popular' ? 'selected' : '' }}>Most
                                        Popular</option>
                                </select>
                            </div>
                        </div>

                        <hr class="my-3">

                        <!-- Clear Filters -->
                        <a href="{{ route('category.show', $category->slug) }}"
                            class="btn btn-outline-secondary btn-sm w-100">
                            <i class="fas fa-redo"></i> Clear Filters
                        </a>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="col-lg-9">
                <!-- Products Count -->
                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        Showing <strong>{{ $products->count() }}</strong> of <strong>{{ $products->total() }}</strong>
                        products
                    </small>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-secondary active" id="gridView" title="Grid View">
                            <i class="fas fa-th-large"></i>
                        </button>
                        <button type="button" class="btn btn-outline-secondary" id="listView" title="List View">
                            <i class="fas fa-list"></i>
                        </button>
                    </div>
                </div>

                <!-- Products Grid -->
                @if ($products->count() > 0)
                    <div class="row g-3" id="productsContainer">
                        @foreach ($products as $product)
                            <div class="col-md-6 col-lg-4">
                                <div class="product-card">
                                    <!-- Product Image Container -->
                                    <div class="product-img-container">
                                        @php
                                            $image = $product->primaryImage ?? $product->images->first();
                                        @endphp
                                        <img src="{{ $image ? asset('storage/' . $image->image_path) : 'https://via.placeholder.com/300x200' }}"
                                            alt="{{ $product->name }}" class="product-img">

                                        <!-- Badges -->
                                        <div class="product-badges">
                                            @if ($product->has_discount)
                                                <span class="discount-badge">{{ $product->discount_percentage }}%
                                                    OFF</span>
                                            @endif
                                            @if ($product->is_featured)
                                                <span class="featured-badge">Featured</span>
                                            @endif
                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="product-actions">
                                            <button class="action-btn quick-view-btn"
                                                data-product-id="{{ $product->id }}" title="Quick View">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button
                                                class="action-btn wishlist-btn {{ Auth::check() && $product->isInWishlist() ? 'active' : '' }}"
                                                data-product-id="{{ $product->id }}" title="Add to Wishlist">
                                                <i class="fas fa-heart"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Product Content -->
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

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $products->links('pagination::bootstrap-5') }}
                    </div>
                @else
                    <div class="alert alert-info text-center py-5">
                        <i class="fas fa-inbox fa-3x mb-3"></i>
                        <p>No products found in this category.</p>
                        <a href="{{ route('shop') }}" class="btn btn-primary btn-sm">Browse All Products</a>
                    </div>
                @endif
            </div>
        </div>
    </div>

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

        /* Product Card Styles */
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
            transform: translateY(-15px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .product-img-container {
            position: relative;
            overflow: hidden;
            width: 100%;
            aspect-ratio: 1/1.03;
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

        .rating-container {
            display: flex;
            align-items: center;
            gap: 6px;
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

        .product-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 10px;
            margin-top: auto;
            border-top: 1px solid #e9ecef;
            gap: 8px;
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
            cursor: pointer;
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

        .filter-section {
            padding: 0.5rem 0;
        }

        @media (max-width: 768px) {
            .product-card {
                height: auto;
            }

            .product-img-container {
                width: 100%;
                aspect-ratio: 1/1.03;
            }
        }
    </style>
@endsection

@section('scripts')
    <script>
        // Enable marquee only when price line overflows (per card)
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

        // Grid/List view toggle
        document.getElementById('gridView')?.addEventListener('click', function() {
            this.classList.add('active');
            document.getElementById('listView').classList.remove('active');
            document.getElementById('productsContainer').classList.remove('list-view');
        });

        document.getElementById('listView')?.addEventListener('click', function() {
            this.classList.add('active');
            document.getElementById('gridView').classList.remove('active');
            document.getElementById('productsContainer').classList.add('list-view');
        });

        // Price range filter
        const maxPriceInput = document.getElementById('maxPriceInput');
        const minPriceInput = document.getElementById('minPriceInput');
        const priceRange = document.getElementById('priceRange');
        const maxPriceDisplay = document.getElementById('maxPriceDisplay');

        if (priceRange) {
            priceRange.addEventListener('input', (e) => {
                maxPriceDisplay.textContent = e.target.value;
            });
        }

        document.getElementById('applyPriceBtn')?.addEventListener('click', () => {
            const minPrice = minPriceInput.value || 0;
            const maxPrice = maxPriceInput.value || priceRange.value;
            const url = new URL(window.location);
            url.searchParams.set('min_price', minPrice);
            url.searchParams.set('max_price', maxPrice);
            window.location = url;
        });

        // Sort functionality
        document.getElementById('sortBy')?.addEventListener('change', function() {
            const url = new URL(window.location);
            url.searchParams.set('sort_by', this.value);
            window.location = url;
        });

        // Search functionality
        document.getElementById('searchBtn')?.addEventListener('click', () => {
            const searchQuery = document.getElementById('searchInput').value;
            const url = new URL(window.location);
            if (searchQuery) {
                url.searchParams.set('search', searchQuery);
            } else {
                url.searchParams.delete('search');
            }
            window.location = url;
        });

        document.getElementById('searchInput')?.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                document.getElementById('searchBtn').click();
            }
        });

        // Quick View Modal
        document.addEventListener('DOMContentLoaded', function() {
            // Quick View Function
            window.showQuickView = async function(productId) {
                try {
                    const response = await fetch(`/product/quick-view/${productId}`);
                    const data = await response.json();

                    if (data.success) {
                        const modal = new bootstrap.Modal(document.getElementById('quickViewModal'));

                        // Clear previous content
                        document.getElementById('gallery-slides').innerHTML = '';
                        document.getElementById('gallery-thumbs').innerHTML = '';
                        document.getElementById('product-details').innerHTML = '';

                        // Load product images from the data
                        if (data.product && data.product.images) {
                            loadProductImages(data.product.images, data.product.video_url);
                        }

                        // Load product details
                        document.getElementById('product-details').innerHTML = data.html;

                        // Show modal
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
                    alert('Failed to load product details');
                }
            };

            // Load Product Images
            window.loadProductImages = function(images, videoUrl = null) {
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
            };

            // Initialize Gallery Slider in Modal
            window.initGallerySlider = function() {
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
            };

            // Auto-play video in modal
            window.playVideoInModal = function() {
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
            };

            // Pause all videos
            window.pauseAllVideos = function() {
                document.querySelectorAll('#quickViewModal video').forEach(video => {
                    video.pause();
                });
            };

            // Handle quick view button clicks
            document.addEventListener('click', function(e) {
                if (e.target.closest('.quick-view-btn')) {
                    e.preventDefault();
                    e.stopPropagation();
                    const productId = e.target.closest('.quick-view-btn').getAttribute('data-product-id');
                    window.showQuickView(productId);
                }
            });

            // Resume sliders when modal closes
            const quickViewModal = document.getElementById('quickViewModal');
            if (quickViewModal) {
                quickViewModal.addEventListener('hidden.bs.modal', function() {
                    pauseAllVideos();
                });
            }
        });

        // Wishlist toggle
        document.querySelectorAll('.wishlist-btn').forEach(btn => {
            btn.addEventListener('click', async (e) => {
                e.preventDefault();
                const productId = btn.dataset.productId;
                // Actual wishlist toggle (global auth guard will intercept if not logged in)
                fetch(`/wishlist/toggle/${productId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }).then(() => {
                    btn.classList.toggle('active');
                    if (window.Toast) {
                        window.Toast.fire({
                            icon: 'success',
                            title: 'Wishlist updated'
                        });
                    }
                }).catch(() => {
                    if (window.Toast) {
                        window.Toast.fire({
                            icon: 'error',
                            title: 'Failed to update wishlist'
                        });
                    }
                });
            });
        });

        // Add to cart (use global addToCart)
        document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const productId = btn.dataset.productId;
                if (typeof addToCart === 'function') {
                    addToCart(productId, 1);
                }
            });
        });
    </script>

    <!-- Quick View Modal -->
    <div class="modal fade quick-view-modal" id="quickViewModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
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

    <style>
        /* Quick View Modal Styles */
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
            content: '';
            position: absolute;
        }

        .product-quick-view {
            padding: 20px;
        }

        .product-quick-view h4 {
            color: #212529;
        }

        .product-quick-view .price {
            margin: 15px 0;
        }

        @media (max-width: 768px) {
            .quick-view-modal .modal-dialog {
                margin: 0.5rem;
                max-width: calc(100% - 1rem);
            }

            .product-gallery-slider {
                height: 300px;
            }
        }
    </style>
@endsection

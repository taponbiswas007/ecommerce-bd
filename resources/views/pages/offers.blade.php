@extends('layouts.app')

@section('title', 'Special Offers - ElectroHub')
@section('description', 'Discover amazing deals and discounts on electronics at ElectroHub.')

@section('content')
    <div class="container py-5">
        <!-- Page Header -->
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 mb-3">Special Offers</h1>
                <p class="lead text-muted">Amazing Deals on Electronics & Gadgets</p>
                <div class="border-bottom border-primary border-3 mx-auto" style="width: 100px;"></div>
            </div>
        </div>

        <!-- Flash Sale Banner -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="card border bg-gradient-danger text-white shadow-lg overflow-hidden">
                    <div class="row g-0">
                        <div class="col-md-8">
                            <div class="card-body p-5">
                                <div class="d-flex align-items-center mb-3">
                                    <span class="badge bg-white text-danger fs-6 me-3">FLASH SALE</span>
                                    <div class="countdown-timer">
                                        <div class="d-flex gap-2">
                                            <div class="text-center">
                                                <div class="bg-white text-danger rounded py-1 px-2"
                                                    style="min-width: 40px;">
                                                    <span id="hours">00</span>
                                                </div>
                                                <small class="text-white-50">HOURS</small>
                                            </div>
                                            <div class="text-white pt-2">:</div>
                                            <div class="text-center">
                                                <div class="bg-white text-danger rounded py-1 px-2"
                                                    style="min-width: 40px;">
                                                    <span id="minutes">00</span>
                                                </div>
                                                <small class="text-white-50">MINUTES</small>
                                            </div>
                                            <div class="text-white pt-2">:</div>
                                            <div class="text-center">
                                                <div class="bg-white text-danger rounded py-1 px-2"
                                                    style="min-width: 40px;">
                                                    <span id="seconds">00</span>
                                                </div>
                                                <small class="text-white-50">SECONDS</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <h2 class="card-title mb-3">Up to 50% OFF on Top Brands</h2>
                                <p class="card-text mb-4">Don't miss out on our biggest sale of the year. Limited time offer
                                    on smartphones, laptops, and home appliances.</p>
                                <a href="#featured-offers" class="btn btn-light btn-lg">
                                    <i class="fas fa-bolt me-2"></i> Shop Now
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4 d-none d-md-block">
                            <div class="h-100"
                                style="background: url('https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da') center/cover;">
                                <!-- Background image -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Featured Offers -->
        <div class="row mb-5" id="featured-offers">
            <div class="col-12 mb-4">
                <h2 class="mb-3">Featured Offers</h2>
                <p class="text-muted">Handpicked deals you don't want to miss</p>
            </div>

            @forelse($featuredOffers as $product)
                <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                    <div class="card product-card h-100 border shadow-sm hover-lift">
                        <div class="position-relative">
                            @if ($product->discount_price)
                                <span class="badge bg-danger position-absolute top-0 start-0 m-3">
                                    {{ round((($product->base_price - $product->discount_price) / $product->base_price) * 100) }}%
                                    OFF
                                </span>
                            @endif
                            @if ($product->is_featured)
                                <span class="badge bg-warning position-absolute top-0 end-0 m-3">Featured</span>
                            @endif

                            <a href="{{ route('product.show', $product->slug) }}">
                                <img src="{{ $product->featured_image ? asset('storage/' . $product->featured_image->image_path) : 'https://via.placeholder.com/300' }}"
                                    class="card-img-top" alt="{{ $product->name }}"
                                    style="height: 200px; object-fit: cover;">
                            </a>

                            <div class="position-absolute bottom-0 start-0 w-100 p-3 bg-dark bg-opacity-50">
                                <div class="text-white small">
                                    <i class="fas fa-clock me-1"></i> Offer ends in 2 days
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="mb-2">
                                <span class="badge bg-info">{{ $product->category->name ?? 'Uncategorized' }}</span>
                            </div>

                            <h5 class="card-title">
                                <a href="{{ route('product.show', $product->slug) }}"
                                    class="text-decoration-none text-dark">
                                    {{ Str::limit($product->name, 50) }}
                                </a>
                            </h5>

                            <p class="card-text text-muted small">
                                {{ Str::limit($product->short_description, 80) }}
                            </p>

                            @php
                                $tieredPrices = $product->prices()->orderBy('min_quantity', 'asc')->get();
                                $unit = $product->unit ? $product->unit->symbol : '';
                            @endphp
                            <div class="price-marquee" data-price-marquee>
                                <div class="price-marquee-inner">
                                    @if ($product->discount_price)
                                        <span
                                            class="price-chip main">৳{{ number_format($product->discount_price, 0) }}</span>
                                        <span class="price-chip old">৳{{ number_format($product->base_price, 0) }}</span>
                                    @else
                                        <span class="price-chip main">৳{{ number_format($product->base_price, 0) }}</span>
                                    @endif
                                    @foreach ($tieredPrices as $price)
                                        <span class="price-chip tier">৳{{ number_format($price->price, 0) }}
                                            <small>({{ $price->min_quantity }}{{ $price->max_quantity ? ' - ' . $price->max_quantity : '+' }}{{ $unit ? ' ' . $unit : '' }})</small></span>
                                    @endforeach
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button class="btn btn-primary add-to-cart-btn" data-product-id="{{ $product->id }}">
                                    <i class="fas fa-cart-plus me-2"></i> Add to Cart
                                </button>
                                <button
                                    class="btn btn-outline-primary wishlist-btn {{ Auth::check() && $product->isInWishlist() ? 'active' : '' }}"
                                    data-product-id="{{ $product->id }}"
                                    data-wishlist-url="{{ route('wishlist.toggle', $product) }}">
                                    <i class="far fa-heart me-2"></i> Add to Wishlist
                                </button>
                            </div>
                        </div>

                        <div class="card-footer bg-transparent border-top">
                            <div class="row text-center">
                                <div class="col-4 border-end">
                                    <small class="text-muted d-block">Stock</small>
                                    <strong class="{{ $product->stock_quantity > 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $product->stock_quantity }}
                                    </strong>
                                </div>
                                <div class="col-4 border-end">
                                    <small class="text-muted d-block">Sold</small>
                                    <strong>{{ $product->sold_count }}</strong>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted d-block">Rating</small>
                                    <strong class="text-warning">
                                        <i class="fas fa-star"></i> {{ number_format($product->average_rating, 1) }}
                                    </strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-gift fa-4x text-muted mb-3"></i>
                        <h3>No Featured Offers Available</h3>
                        <p class="text-muted">Check back soon for amazing deals!</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- All Discounted Products -->
        <div class="row mb-5">
            <div class="col-12 mb-4">
                <h2 class="mb-3">All Discounted Products</h2>
                <p class="text-muted">Save big on these amazing products</p>
            </div>

            @forelse($discountedProducts as $product)
                <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                    <div class="card product-card h-100 border shadow-sm">
                        <div class="position-relative">
                            @if ($product->discount_price)
                                <span class="badge bg-danger position-absolute top-0 start-0 m-2">
                                    {{ round((($product->base_price - $product->discount_price) / $product->base_price) * 100) }}%
                                    OFF
                                </span>
                            @endif

                            <a href="{{ route('product.show', $product->slug) }}">
                                <img src="{{ $product->featured_image ? asset('storage/' . $product->featured_image->image_path) : 'https://via.placeholder.com/300' }}"
                                    class="card-img-top" alt="{{ $product->name }}"
                                    style="height: 180px; object-fit: cover;">
                            </a>
                        </div>

                        <div class="card-body">
                            <h6 class="card-title">
                                <a href="{{ route('product.show', $product->slug) }}"
                                    class="text-decoration-none text-dark">
                                    {{ Str::limit($product->name, 40) }}
                                </a>
                            </h6>

                            @php
                                $tieredPrices = $product->prices()->orderBy('min_quantity', 'asc')->get();
                                $unit = $product->unit ? $product->unit->symbol : '';
                            @endphp
                            <div class="price-marquee" data-price-marquee>
                                <div class="price-marquee-inner">
                                    @if ($product->discount_price)
                                        <span
                                            class="price-chip main">৳{{ number_format($product->discount_price, 0) }}</span>
                                        <span class="price-chip old">৳{{ number_format($product->base_price, 0) }}</span>
                                    @else
                                        <span class="price-chip main">৳{{ number_format($product->base_price, 0) }}</span>
                                    @endif
                                    @foreach ($tieredPrices as $price)
                                        <span class="price-chip tier">৳{{ number_format($price->price, 0) }}
                                            <small>({{ $price->min_quantity }}{{ $price->max_quantity ? ' - ' . $price->max_quantity : '+' }}{{ $unit ? ' ' . $unit : '' }})</small></span>
                                    @endforeach
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <button class="btn btn-sm btn-primary add-to-cart-btn"
                                    data-product-id="{{ $product->id }}">
                                    <i class="fas fa-cart-plus"></i>
                                </button>
                                <button
                                    class="btn btn-sm btn-outline-primary wishlist-btn {{ Auth::check() && $product->isInWishlist() ? 'active' : '' }}"
                                    data-product-id="{{ $product->id }}"
                                    data-wishlist-url="{{ route('wishlist.toggle', $product) }}">
                                    <i class="fas fa-heart"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-tags fa-4x text-muted mb-3"></i>
                        <h3>No Discounted Products Available</h3>
                        <p class="text-muted">All our products are currently at regular prices.</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($discountedProducts->hasPages())
            <div class="row">
                <div class="col-12">
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">
                            {{ $discountedProducts->links() }}
                        </ul>
                    </nav>
                </div>
            </div>
        @endif

        <!-- Special Categories Offers -->
        <div class="row mb-5">
            <div class="col-12 mb-4">
                <h2 class="mb-3">Category Specials</h2>
                <p class="text-muted">Exclusive deals by category</p>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card border shadow-sm h-100 hover-lift">
                    <div class="card-body p-4 text-center">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex p-4 mb-3">
                            <i class="fas fa-mobile-alt fa-3x text-primary"></i>
                        </div>
                        <h4 class="card-title mb-2">Smartphones</h4>
                        <p class="card-text text-muted mb-3">Up to 40% OFF on latest models</p>
                        <a href="{{ route('category.show', 'smartphones') }}" class="btn btn-outline-primary">
                            View Deals <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card border shadow-sm h-100 hover-lift">
                    <div class="card-body p-4 text-center">
                        <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex p-4 mb-3">
                            <i class="fas fa-laptop fa-3x text-success"></i>
                        </div>
                        <h4 class="card-title mb-2">Laptops & Computers</h4>
                        <p class="card-text text-muted mb-3">Student discounts up to 35%</p>
                        <a href="{{ route('category.show', 'laptops') }}" class="btn btn-outline-success">
                            View Deals <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card border shadow-sm h-100 hover-lift">
                    <div class="card-body p-4 text-center">
                        <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex p-4 mb-3">
                            <i class="fas fa-tv fa-3x text-warning"></i>
                        </div>
                        <h4 class="card-title mb-2">Home Appliances</h4>
                        <p class="card-text text-muted mb-3">Bundle deals with free installation</p>
                        <a href="{{ route('category.show', 'home-appliances') }}" class="btn btn-outline-warning">
                            View Deals <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Newsletter Subscription -->
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card border bg-gradient-primary text-white shadow-lg">
                    <div class="card-body p-5 text-center">
                        <h2 class="card-title mb-3">Never Miss a Deal!</h2>
                        <p class="card-text mb-4">Subscribe to our newsletter and be the first to know about exclusive
                            offers, new arrivals, and special promotions.</p>

                        <form class="row g-3 justify-content-center">
                            <div class="col-md-8">
                                <div class="input-group input-group-lg">
                                    <input type="email" class="form-control" placeholder="Enter your email address"
                                        required>
                                    <button class="btn btn-light" type="submit">
                                        Subscribe
                                    </button>
                                </div>
                            </div>
                        </form>

                        <p class="small mt-3 mb-0">
                            <i class="fas fa-shield-alt me-2"></i> We respect your privacy. Unsubscribe at any time.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .hover-lift {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, var(--electric-blue), var(--electric-purple)) !important;
        }

        .bg-gradient-danger {
            background: linear-gradient(135deg, #ff416c, #ff4b2b) !important;
        }

        .product-card {
            transition: all 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        /* Price marquee and chips */
        .price-marquee {
            position: relative;
            overflow: hidden;
            white-space: nowrap;
            margin: 8px 0 12px;
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
    </style>

    <script>
        // Countdown Timer
        function updateCountdown() {
            const now = new Date();
            const tomorrow = new Date(now);
            tomorrow.setDate(tomorrow.getDate() + 1);
            tomorrow.setHours(23, 59, 59, 999);

            const diff = tomorrow - now;

            const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((diff % (1000 * 60)) / 1000);

            document.getElementById('hours').textContent = hours.toString().padStart(2, '0');
            document.getElementById('minutes').textContent = minutes.toString().padStart(2, '0');
            document.getElementById('seconds').textContent = seconds.toString().padStart(2, '0');
        }

        // Update countdown every second
        setInterval(updateCountdown, 1000);
        updateCountdown();
    </script>
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

        document.addEventListener('DOMContentLoaded', function() {
            initPriceMarquee();
            window.addEventListener('resize', initPriceMarquee);

            // Wishlist toggle (auth required; global guard will prompt if guest)
            document.querySelectorAll('.wishlist-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = this.getAttribute('data-wishlist-url');
                    if (!url) return;
                    this.classList.toggle('active');
                    fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                        .then(r => r.json().catch(() => ({})))
                        .then(data => {
                            if (window.Toast) {
                                if (data && data.success) {
                                    window.Toast.fire({
                                        icon: 'success',
                                        title: data.message || 'Wishlist updated'
                                    });
                                } else {
                                    window.Toast.fire({
                                        icon: 'info',
                                        title: 'Wishlist updated'
                                    });
                                }
                            }
                        })
                        .catch(() => {
                            if (window.Toast) {
                                window.Toast.fire({
                                    icon: 'error',
                                    title: 'Wishlist request failed'
                                });
                            }
                        });
                });
            });

            // Add to cart binds to global helper
            document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const id = this.getAttribute('data-product-id');
                    if (window.addToCart) {
                        window.addToCart(parseInt(id, 10), 1);
                    }
                });
            });
        });
    </script>
@endsection

@extends('layouts.app')

@section('title', 'Home')

@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <style>
        .hero-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .category-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .product-image {
            height: 200px;
            object-fit: cover;
        }

        @media (max-width: 768px) {
            .product-image {
                height: 150px;
            }
        }
    </style>
@endsection

@section('content')
    <!-- Hero Section -->
    <section class="hero-gradient text-white py-16 md:py-24">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row items-center">
                <div class="md:w-1/2 mb-10 md:mb-0">
                    <h1 class="text-4xl md:text-6xl font-bold mb-4 leading-tight">
                        Shop The Best Products <br>
                        <span class="text-yellow-300">From Bangladesh</span>
                    </h1>
                    <p class="text-xl mb-8 opacity-90">
                        Discover amazing products with best prices. Fast delivery across Bangladesh.
                    </p>
                    <div class="flex space-x-4">
                        <a href="{{ route('shop') }}"
                            class="bg-white text-primary-600 hover:bg-gray-100 px-8 py-3 rounded-lg font-semibold text-lg transition duration-300">
                            Shop Now
                        </a>
                        <a href="#featured"
                            class="border-2 border-white text-white hover:bg-white hover:text-primary-600 px-8 py-3 rounded-lg font-semibold text-lg transition duration-300">
                            View Featured
                        </a>
                    </div>
                </div>
                <div class="md:w-1/2">
                    <div class="relative">
                        <div
                            class="absolute -top-6 -left-6 w-72 h-72 bg-yellow-400 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob">
                        </div>
                        <div
                            class="absolute -bottom-8 -right-4 w-72 h-72 bg-pink-400 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000">
                        </div>
                        <div class="relative">
                            <img src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                                alt="Hero Image" class="rounded-2xl shadow-2xl">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">Shop By Categories</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
                @php
                    $categories = \App\Models\Category::whereNull('parent_id')
                        ->where('is_active', true)
                        ->withCount('products')
                        ->orderBy('order')
                        ->limit(12)
                        ->get();
                @endphp

                @foreach ($categories as $category)
                    <a href="{{ route('category.show', $category->slug) }}"
                        class="category-card bg-white rounded-xl p-6 text-center shadow-md hover:shadow-xl">
                        <div class="w-16 h-16 mx-auto mb-4 bg-primary-100 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-800 mb-2">{{ $category->name }}</h3>
                        <p class="text-sm text-gray-600">{{ $category->products_count }} products</p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section id="featured" class="py-16">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center mb-10">
                <div>
                    <h2 class="text-3xl font-bold">Featured Products</h2>
                    <p class="text-gray-600 mt-2">Most popular products this week</p>
                </div>
                <a href="{{ route('shop') }}" class="text-primary-600 hover:text-primary-800 font-semibold">
                    View All →
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                @php
                    $featuredProducts = \App\Models\Product::with('images')
                        ->where('is_featured', true)
                        ->where('is_active', true)
                        ->where('stock_quantity', '>', 0)
                        ->orderBy('created_at', 'desc')
                        ->limit(10)
                        ->get();
                @endphp

                @foreach ($featuredProducts as $product)
                    <div
                        class="product-card bg-white rounded-lg overflow-hidden shadow-md hover:shadow-xl transition duration-300">
                        <div class="relative">
                            <a href="{{ route('product.show', $product->slug) }}">
                                <img src="{{ $product->primary_image ? asset('storage/' . $product->primary_image->image_path) : 'https://via.placeholder.com/300x200' }}"
                                    alt="{{ $product->name }}" class="product-image w-full">
                            </a>
                            @if ($product->has_discount)
                                <span
                                    class="absolute top-3 left-3 bg-red-500 text-white px-2 py-1 rounded text-xs font-bold">
                                    -{{ $product->discount_percentage }}%
                                </span>
                            @endif
                            <button class="absolute top-3 right-3 bg-white p-2 rounded-full shadow-md hover:bg-gray-100">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </button>
                        </div>

                        <div class="p-4">
                            <a href="{{ route('product.show', $product->slug) }}">
                                <h3 class="font-semibold text-gray-800 mb-2 hover:text-primary-600 line-clamp-2">
                                    {{ $product->name }}
                                </h3>
                            </a>

                            <div class="flex items-center mb-3">
                                <div class="star-rating">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $product->average_rating)
                                            <svg class="star w-4 h-4 text-yellow-400" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        @else
                                            <svg class="star-empty w-4 h-4 text-gray-300" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        @endif
                                    @endfor
                                </div>
                                <span class="text-sm text-gray-600 ml-2">({{ $product->total_reviews }})</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <div>
                                    @if ($product->has_discount)
                                        <span
                                            class="text-xl font-bold text-gray-900">৳{{ number_format($product->discount_price, 2) }}</span>
                                        <span
                                            class="text-sm text-gray-500 line-through ml-2">৳{{ number_format($product->base_price, 2) }}</span>
                                    @else
                                        <span
                                            class="text-xl font-bold text-gray-900">৳{{ number_format($product->base_price, 2) }}</span>
                                    @endif
                                </div>

                                <button
                                    class="add-to-cart bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition duration-300"
                                    data-product-id="{{ $product->id }}">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Add
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto mb-4 bg-primary-100 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Secure Payment</h3>
                    <p class="text-gray-600">100% secure payment with SSL encryption</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 mx-auto mb-4 bg-primary-100 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Fast Delivery</h3>
                    <p class="text-gray-600">Quick delivery across Bangladesh</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 mx-auto mb-4 bg-primary-100 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2">24/7 Support</h3>
                    <p class="text-gray-600">Dedicated customer support team</p>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add to cart functionality
            document.querySelectorAll('.add-to-cart').forEach(button => {
                    button.addEventListener('click', function() {
                            const productId = this.getAttribute('data-product-id');

                            @auth
                            addToCart(productId, 1);
                        @else
                            showLoginModal(() => {
                                addToCart(productId, 1);
                            });
                        @endauth
                    });
            });

        function addToCart(productId, quantity) {
            fetch('/cart/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
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

                        // Update cart count
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
                        title: 'An error occurred'
                    });
                });
        }

        function updateCartCount(count) {
            const cartCountElement = document.querySelector('.cart-count');
            if (cartCountElement) {
                cartCountElement.textContent = count;
            }
        }

        function showLoginModal(callback) {
            Swal.fire({
                title: 'Login Required',
                text: 'You need to login to add items to cart',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Login',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/login?redirect=' + encodeURIComponent(window.location.href);
                }
            });
        }

        // Initialize Swiper
        const swiper = new Swiper('.hero-swiper', {
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
            },
        });
        });
    </script>
@endsection

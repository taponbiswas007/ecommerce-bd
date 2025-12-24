@extends('layouts.app')

@section('title', 'My Wishlist - ElectroHub')

@section('styles')
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6f42c1;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --success-color: #198754;
        }

        .wishlist-page {
            min-height: 70vh;
            padding: 40px 0;
        }

        .wishlist-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 30px 0;
            margin-bottom: 30px;
            border-radius: 0 0 20px 20px;
        }

        .wishlist-item {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border: 1px solid #dee2e6;
        }

        .wishlist-item:hover {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
            transform: translateY(-5px);
        }

        .wishlist-item-image {
            width: 120px;
            height: 120px;
            object-fit: contain;
            border-radius: 10px;
            background: #f8f9fa;
            padding: 10px;
        }

        .remove-btn {
            background: var(--danger-color);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 20px;
            transition: all 0.3s ease;
        }

        .remove-btn:hover {
            background: #c82333;
            transform: scale(1.05);
        }

        .add-to-cart-btn {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .add-to-cart-btn:hover {
            background: #0b5ed7;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
        }

        .empty-wishlist {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        }

        .empty-wishlist i {
            font-size: 4rem;
            color: #dee2e6;
            margin-bottom: 20px;
        }
    </style>
@endsection

@section('content')
    <div class="wishlist-page">
        <div class="container">
            <!-- Header -->
            <div class="wishlist-header text-center">
                <h1 class="fw-bold mb-3">
                    <i class="fas fa-heart me-3"></i>My Wishlist
                </h1>
                <p class="mb-0">Your saved items for later</p>
            </div>

            @if ($wishlistItems->count() > 0)
                <div class="row">
                    @foreach ($wishlistItems as $item)
                        <div class="col-12">
                            <div class="wishlist-item">
                                <div class="row align-items-center">
                                    <div class="col-md-2 text-center">
                                        @php
                                            $image = $item->product->primaryImage ?? $item->product->images->first();
                                        @endphp
                                        <img src="{{ $image ? asset('storage/' . $image->image_path) : 'https://via.placeholder.com/120x120' }}"
                                            alt="{{ $item->product->name }}" class="wishlist-item-image">
                                    </div>

                                    <div class="col-md-6">
                                        <h5 class="mb-2">
                                            <a href="{{ route('product.show', $item->product->slug) }}"
                                                class="text-decoration-none text-dark">
                                                {{ $item->product->name }}
                                            </a>
                                        </h5>
                                        <p class="text-muted mb-2">{{ $item->product->category->name ?? 'Uncategorized' }}
                                        </p>

                                        <div class="rating-container mb-2">
                                            <div class="rating-stars">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= floor($item->product->average_rating ?? 0))
                                                        <i class="fas fa-star"></i>
                                                    @elseif ($i - 0.5 <= $item->product->average_rating ?? 0)
                                                        <i class="fas fa-star-half-alt"></i>
                                                    @else
                                                        <i class="far fa-star"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <span
                                                class="rating-count ms-2">({{ $item->product->total_reviews ?? 0 }})</span>
                                        </div>

                                        <div class="product-price">
                                            @if ($item->product->has_discount)
                                                <span
                                                    class="current-price fw-bold">৳{{ number_format($item->product->discount_price, 2) }}</span>
                                                <span
                                                    class="old-price ms-2">৳{{ number_format($item->product->base_price, 2) }}</span>
                                                <span class="save-percent ms-2">Save
                                                    {{ $item->product->discount_percentage }}%</span>
                                            @else
                                                <span
                                                    class="current-price fw-bold">৳{{ number_format($item->product->base_price, 2) }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-4 text-end">
                                        <div class="d-flex flex-column gap-2 align-items-end">
                                            <button class="remove-btn"
                                                onclick="removeFromWishlist({{ $item->product->id }}, this)">
                                                <i class="fas fa-trash me-2"></i>Remove
                                            </button>
                                            <button class="add-to-cart-btn"
                                                onclick="addToCart({{ $item->product->id }}, 1)">
                                                <i class="fas fa-cart-plus me-2"></i>Add to Cart
                                            </button>
                                            <a href="{{ route('product.show', $item->product->slug) }}"
                                                class="btn btn-outline-primary">
                                                View Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-wishlist">
                    <i class="fas fa-heart-broken"></i>
                    <h3 class="mb-3">Your wishlist is empty</h3>
                    <p class="text-muted mb-4">Start adding items you love to your wishlist!</p>
                    <a href="{{ route('shop') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-shopping-bag me-2"></i>Start Shopping
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function removeFromWishlist(productId, button) {
            if (confirm('Are you sure you want to remove this item from your wishlist?')) {
                fetch(`/wishlist/remove/${productId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Remove the item from DOM
                            button.closest('.wishlist-item').remove();

                            // Update wishlist count
                            updateWishlistCount(data.wishlist_count);

                            Toast.fire({
                                icon: 'success',
                                title: data.message
                            });

                            // Check if wishlist is now empty
                            if (document.querySelectorAll('.wishlist-item').length === 0) {
                                location.reload(); // Reload to show empty state
                            }
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: data.message
                            });
                        }
                    })
                    .catch(error => {
                        Toast.fire({
                            icon: 'error',
                            title: 'Failed to remove item'
                        });
                    });
            }
        }

        function updateWishlistCount(count) {
            const wishlistCount = document.querySelector('.wishlist-count');
            if (wishlistCount) {
                wishlistCount.textContent = count;
                wishlistCount.style.display = count > 0 ? 'flex' : 'none';
            }
        }
    </script>
@endsection

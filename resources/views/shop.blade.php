@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h1 class="h3 mb-0">Shop Products</h1>
                <small class="text-muted">Browse our extensive collection</small>
            </div>
            <div class="col-md-6 text-end">
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
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="fas fa-filter"></i> Filters
                        </h5>

                        <!-- Category Filter -->
                        <div class="filter-section mb-4">
                            <h6 class="text-uppercase small fw-bold mb-3">
                                <a href="#categoryCollapse" data-bs-toggle="collapse"
                                    class="text-dark text-decoration-none">
                                    <i class="fas fa-chevron-down"></i> Categories
                                </a>
                            </h6>
                            <div class="collapse show" id="categoryCollapse">
                                <div class="list-unstyled">
                                    @forelse($categories as $category)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input category-filter" type="checkbox"
                                                value="{{ $category->slug }}" id="cat_{{ $category->id }}"
                                                {{ request('category') == $category->slug ? 'checked' : '' }}>
                                            <label class="form-check-label small" for="cat_{{ $category->id }}">
                                                {{ $category->name }}
                                            </label>
                                        </div>
                                    @empty
                                        <p class="text-muted small">No categories available</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <hr class="my-3">

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
                                        <small>₹ <span id="minPriceDisplay">0</span></small>
                                        <small>₹ <span
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
                                    <option value="discount" {{ request('sort_by') == 'discount' ? 'selected' : '' }}>
                                        Highest Discount</option>
                                </select>
                            </div>
                        </div>

                        <hr class="my-3">

                        <!-- Clear Filters -->
                        <a href="{{ route('shop') }}" class="btn btn-outline-secondary btn-sm w-100">
                            <i class="fas fa-redo"></i> Clear All Filters
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
                        <button type="button" class="btn btn-outline-secondary active" id="gridView"
                            title="Grid View">
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
                            <div class="col-md-6 col-lg-4 product-card">
                                <div class="card h-100 border-0 shadow-sm hover-shadow transition">
                                    <!-- Product Image -->
                                    <div class="position-relative overflow-hidden" style="height: 250px;">
                                        @if ($product->primaryImage)
                                            <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}"
                                                alt="{{ $product->name }}"
                                                class="img-fluid w-100 h-100 object-fit-cover">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center h-100">
                                                <i class="fas fa-image text-muted fa-3x"></i>
                                            </div>
                                        @endif

                                        <!-- Badges -->
                                        <div class="position-absolute top-0 start-0 p-2">
                                            @if ($product->discount_price && $product->discount_price < $product->base_price)
                                                @php
                                                    $discount = round(
                                                        (($product->base_price - $product->discount_price) /
                                                            $product->base_price) *
                                                            100,
                                                    );
                                                @endphp
                                                <span class="badge bg-danger">-{{ $discount }}%</span>
                                            @endif
                                            @if ($product->is_featured)
                                                <span class="badge bg-warning">Featured</span>
                                            @endif
                                        </div>

                                        <!-- Wishlist Button -->
                                        <button class="btn btn-light btn-sm position-absolute top-0 end-0 m-2 wishlist-btn"
                                            data-product-id="{{ $product->id }}" title="Add to Wishlist">
                                            <i class="far fa-heart"></i>
                                        </button>
                                    </div>

                                    <div class="card-body d-flex flex-column">
                                        <!-- Category -->
                                        <small class="text-primary mb-2">
                                            {{ $product->category ? $product->category->name : 'Uncategorized' }}
                                        </small>

                                        <!-- Product Name -->
                                        <h6 class="card-title mb-2 flex-grow-1">
                                            <a href="{{ route('product.show', $product->slug) }}"
                                                class="text-dark text-decoration-none">
                                                @if (strlen($product->name) > 50)
                                                    {{ substr($product->name, 0, 50) }}...
                                                @else
                                                    {{ $product->name }}
                                                @endif
                                            </a>
                                        </h6>

                                        <!-- Rating -->
                                        <div class="mb-2">
                                            <small class="text-warning">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star-half"></i>
                                                <span class="text-muted">(24)</span>
                                            </small>
                                        </div>

                                        <!-- Price -->
                                        <div class="mb-3">
                                            @if ($product->discount_price && $product->discount_price < $product->base_price)
                                                <span class="h6 text-danger fw-bold">
                                                    ₹{{ number_format($product->discount_price, 2) }}
                                                </span>
                                                <span class="small text-muted text-decoration-line-through">
                                                    ₹{{ number_format($product->base_price, 2) }}
                                                </span>
                                            @else
                                                <span class="h6 text-danger fw-bold">
                                                    ₹{{ number_format($product->base_price, 2) }}
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Stock Status -->
                                        <small class="mb-3">
                                            @if ($product->stock_quantity > 0)
                                                <span class="badge bg-success">In Stock</span>
                                            @else
                                                <span class="badge bg-danger">Out of Stock</span>
                                            @endif
                                        </small>

                                        <!-- Quick Actions -->
                                        <div class="d-grid gap-2">
                                            <a href="{{ route('product.show', $product->slug) }}"
                                                class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i> View Details
                                            </a>
                                            <button class="btn btn-sm btn-outline-primary add-to-cart-btn"
                                                data-product-id="{{ $product->id }}">
                                                <i class="fas fa-shopping-cart"></i> Add to Cart
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
                        <p>No products found. Try adjusting your filters.</p>
                        <a href="{{ route('shop') }}" class="btn btn-primary btn-sm">Clear Filters</a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .hover-shadow {
            transition: box-shadow 0.3s ease;
        }

        .hover-shadow:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }

        .transition {
            transition: all 0.3s ease;
        }

        .filter-section {
            padding: 0.5rem 0;
        }

        .object-fit-cover {
            object-fit: cover;
        }

        .form-range {
            height: 0.5rem;
            cursor: pointer;
        }
    </style>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Search functionality
            $('#searchBtn').click(function() {
                let search = $('#searchInput').val();
                let params = new URLSearchParams(window.location.search);
                if (search) {
                    params.set('search', search);
                } else {
                    params.delete('search');
                }
                window.location.href = '{{ route('shop') }}?' + params.toString();
            });

            $('#searchInput').keypress(function(e) {
                if (e.which == 13) {
                    $('#searchBtn').click();
                    return false;
                }
            });

            // Category filter
            $('.category-filter').change(function() {
                let category = $(this).is(':checked') ? $(this).val() : '';
                let params = new URLSearchParams(window.location.search);
                if (category) {
                    params.set('category', category);
                } else {
                    params.delete('category');
                }
                window.location.href = '{{ route('shop') }}?' + params.toString();
            });

            // Price range filter
            let maxPrice = {{ request('max_price', '100000') }};
            $('#priceRange').val(maxPrice);
            $('#maxPriceDisplay').text(maxPrice.toLocaleString());

            $('#priceRange').on('input', function() {
                let val = $(this).val();
                $('#maxPriceDisplay').text(val.toLocaleString());
            });

            $('#maxPriceInput').val(maxPrice);
            $('#minPriceInput').val('{{ request('min_price', '') }}');

            $('#applyPriceBtn').click(function() {
                let minPrice = $('#minPriceInput').val() || 0;
                let maxPrice = $('#maxPriceInput').val() || $('#priceRange').attr('max');
                let params = new URLSearchParams(window.location.search);

                if (minPrice) params.set('min_price', minPrice);
                else params.delete('min_price');

                if (maxPrice) params.set('max_price', maxPrice);
                else params.delete('max_price');

                window.location.href = '{{ route('shop') }}?' + params.toString();
            });

            // Sort filter
            $('#sortBy').change(function() {
                let sortBy = $(this).val();
                let params = new URLSearchParams(window.location.search);
                params.set('sort_by', sortBy);
                window.location.href = '{{ route('shop') }}?' + params.toString();
            });

            // Wishlist button
            $('.wishlist-btn').click(function(e) {
                e.preventDefault();
                let productId = $(this).data('product-id');
                $(this).toggleClass('active');
                // Add AJAX call here for actual wishlist functionality
                fetch(`/wishlist/toggle/${productId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .catch(err => console.log(err));
            });

            // Add to cart
            $('.add-to-cart-btn').click(function(e) {
                e.preventDefault();
                let productId = $(this).data('product-id');
                fetch('{{ route('cart.add') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            product_id: productId,
                            quantity: 1
                        })
                    })
                    .then(r => r.json())
                    .then(data => {
                        alert('Added to cart!');
                    })
                    .catch(err => console.log(err));
            });

            // Grid/List view toggle
            $('#listView').click(function() {
                $('#gridView').removeClass('active');
                $(this).addClass('active');
                $('#productsContainer').removeClass('row').addClass('list-view');
                $('.product-card').removeClass('col-md-6 col-lg-4').addClass('col-12');
            });

            $('#gridView').click(function() {
                $('#listView').removeClass('active');
                $(this).addClass('active');
                $('#productsContainer').removeClass('list-view').addClass('row');
                $('.product-card').removeClass('col-12').addClass('col-md-6 col-lg-4');
            });
        });
    </script>
@endsection

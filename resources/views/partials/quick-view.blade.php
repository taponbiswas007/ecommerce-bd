<div class="product-quick-view">
    <h4 class="fw-bold mb-3">{{ $product->name }}</h4>

    <!-- Rating -->
    <div class="rating-container mb-3">
        <div class="rating-stars">
            @php
                $averageRating = $product->average_rating ?? 0;
                $fullStars = floor($averageRating);
                $hasHalfStar = $averageRating - $fullStars >= 0.5;
            @endphp

            @for ($i = 1; $i <= 5; $i++)
                @if ($i <= $fullStars)
                    <i class="fas fa-star text-warning"></i>
                @elseif($i == $fullStars + 1 && $hasHalfStar)
                    <i class="fas fa-star-half-alt text-warning"></i>
                @else
                    <i class="far fa-star text-warning"></i>
                @endif
            @endfor
        </div>
        <span class="rating-count ms-2">({{ $product->total_reviews ?? 0 }} reviews)</span>
    </div>

    <!-- Price -->
    <div class="price mb-4">
        @if ($product->discount_price && $product->base_price > $product->discount_price)
            <h4 class="text-danger fw-bold mb-1">৳{{ number_format($product->discount_price, 2) }}</h4>
            <del class="text-muted">৳{{ number_format($product->base_price, 2) }}</del>
            <span class="badge bg-danger ms-2">
                Save {{ round((($product->base_price - $product->discount_price) / $product->base_price) * 100) }}%
            </span>
        @else
            <h4 class="fw-bold">৳{{ number_format($product->base_price, 2) }}</h4>
        @endif
    </div>

    <!-- Tiered Pricing Display -->
    @php
        $tieredPrices = $product->prices()->orderBy('min_quantity', 'asc')->get();
        $unit = $product->unit ? $product->unit->symbol : '';
    @endphp
    @if ($tieredPrices->count() > 0)
        <div class="tiered-pricing mb-4">
            <strong class="d-block mb-2">Quantity-based Pricing:</strong>
            <div class="price-list">
                @foreach ($tieredPrices as $price)
                    <div class="price-item small mb-2">
                        <span
                            class="qty-range">{{ $price->min_quantity }}{{ $price->max_quantity ? ' - ' . $price->max_quantity : '+' }}{{ $unit ? ' ' . $unit : '' }}</span>
                        <span class="price-value">৳{{ number_format($price->price, 2) }} each</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Short Description -->
    <div class="short-description mb-4">
        <p>{{ $product->short_description }}</p>
    </div>

    <!-- Stock Status -->
    <div class="stock-status mb-4">
        <strong>Availability:</strong>
        @if ($product->stock_quantity > 10)
            <span class="text-success ms-2">
                <i class="fas fa-check-circle"></i> In Stock
            </span>
        @elseif($product->stock_quantity > 0)
            <span class="text-warning ms-2">
                <i class="fas fa-exclamation-triangle"></i>
                Low Stock ({{ $product->stock_quantity }} left)
            </span>
        @else
            <span class="text-danger ms-2">
                <i class="fas fa-times-circle"></i> Out of Stock
            </span>
        @endif
    </div>

    <!-- Add to Cart -->
    @if ($product->stock_quantity > 0)
        <div class="add-to-cart mb-4">
            <div class="input-group mb-3" style="max-width: 200px;">
                <button class="btn btn-outline-secondary" type="button"
                    onclick="quickViewDecreaseQuantity()">-</button>
                <input type="number" id="quick-view-quantity" class="form-control text-center"
                    value="{{ $product->min_order_quantity }}" min="{{ $product->min_order_quantity }}">
                <button class="btn btn-outline-secondary" type="button"
                    onclick="quickViewIncreaseQuantity()">+</button>
            </div>
            <button class="btn btn-primary btn-lg w-100 add-to-cart-btn"
                onclick="addToCartFromQuickView({{ $product->id }})">
                <i class="fas fa-cart-plus me-2"></i> Add to Cart
            </button>
        </div>
    @else
        <div class="alert alert-warning">
            This product is currently out of stock.
        </div>
    @endif

    <!-- View Details Button -->
    <div class="mt-4">
        <a href="{{ route('product.show', $product->slug) }}" class="btn btn-outline-primary w-100">
            <i class="fas fa-external-link-alt me-2"></i> View Full Details
        </a>
    </div>

    <!-- Category -->
    <div class="product-meta mt-4">
        <p class="mb-2"><strong>Category:</strong> {{ $product->category->name ?? 'N/A' }}</p>
        <p class="mb-0"><strong>SKU:</strong> {{ $product->id }}</p>
    </div>
</div>

<style>
    .tiered-pricing {
        background: #f8f9fa;
        padding: 12px;
        border-radius: 8px;
        border-left: 3px solid #0d6efd;
    }

    .price-list {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .price-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 6px 0;
    }

    .qty-range {
        color: #666;
        font-weight: 500;
    }

    .price-value {
        color: #0f5132;
        font-weight: 600;
        background: #d1e7dd;
        padding: 2px 8px;
        border-radius: 4px;
    }
</style>

<script>
    // Quantity functions for quick view
    function quickViewDecreaseQuantity() {
        const input = document.getElementById('quick-view-quantity');
        if (!input) return;
        const min = parseInt(input.min);
        if (parseInt(input.value) > min) {
            input.value = parseInt(input.value) - 1;
        }
    }

    function quickViewIncreaseQuantity() {
        const input = document.getElementById('quick-view-quantity');
        if (!input) return;
        input.value = parseInt(input.value) + 1;
    }

    // Add to cart from quick view
    function addToCartFromQuickView(productId) {
        const quantity = document.getElementById('quick-view-quantity')?.value || 1;
        addToCart(productId, quantity);

        // Close the modal after adding to cart
        const modal = bootstrap.Modal.getInstance(document.getElementById('quickViewModal'));
        if (modal) {
            modal.hide();
        }
    }
</script>

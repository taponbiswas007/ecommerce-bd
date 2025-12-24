@extends('layouts.app')

@section('title', 'Shopping Cart - ElectroHub')

@section('styles')
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6f42c1;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --success-color: #198754;
        }

        .cart-page {
            min-height: 70vh;
        }

        .cart-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 30px 0;
            margin-bottom: 30px;
            border-radius: 0 0 20px 20px;
        }

        .cart-item {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border: 1px solid #dee2e6;
        }

        .cart-item:hover {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
            transform: translateY(-5px);
        }

        .cart-item-image {
            width: 120px;
            height: 120px;
            object-fit: contain;
            border-radius: 10px;
            background: #f8f9fa;
            padding: 10px;
        }

        .cart-item-title {
            font-size: 16px;
            font-weight: 600;
            color: #212529;
            margin-bottom: 5px;
        }

        .cart-item-title a {
            text-decoration: none;
            color: inherit;
        }

        .cart-item-title a:hover {
            color: var(--primary-color);
        }

        .cart-item-price {
            font-size: 18px;
            font-weight: 700;
            color: var(--primary-color);
        }

        .cart-item-old-price {
            font-size: 14px;
            color: #6c757d;
            text-decoration: line-through;
            margin-left: 8px;
        }

        .quantity-selector {
            display: flex;
            align-items: center;
            border: 2px solid #dee2e6;
            border-radius: 10px;
            overflow: hidden;
            width: 130px;
        }

        .quantity-btn {
            background: #f8f9fa;
            border: none;
            width: 40px;
            height: 40px;
            font-size: 18px;
            font-weight: bold;
            color: #495057;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .quantity-btn:hover {
            background: #e9ecef;
        }

        .quantity-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .quantity-input {
            width: 50px;
            height: 40px;
            border: none;
            text-align: center;
            font-size: 16px;
            font-weight: 600;
            background: white;
        }

        .quantity-input:focus {
            outline: none;
        }

        .remove-btn {
            background: none;
            border: none;
            color: var(--danger-color);
            font-size: 20px;
            cursor: pointer;
            padding: 5px;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .remove-btn:hover {
            background: #fff5f5;
            color: #bb2d3b;
        }

        .cart-summary {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            border: 1px solid #dee2e6;
            position: sticky;
            top: 20px;
        }

        .summary-title {
            font-size: 20px;
            font-weight: 700;
            color: #212529;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #dee2e6;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px dashed #dee2e6;
        }

        .summary-label {
            color: #6c757d;
        }

        .summary-value {
            font-weight: 600;
            color: #212529;
        }

        .summary-total {
            display: flex;
            justify-content: space-between;
            font-size: 18px;
            font-weight: 700;
            color: #212529;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 2px solid #dee2e6;
        }

        .summary-total .value {
            color: var(--primary-color);
            font-size: 24px;
        }

        .btn-checkout {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 10px;
            font-size: 18px;
            font-weight: 600;
            width: 100%;
            margin-top: 20px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-checkout:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(13, 110, 253, 0.3);
        }

        .btn-checkout:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
            box-shadow: none !important;
        }

        .btn-continue-shopping {
            background: white;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
            padding: 15px 30px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            width: 100%;
            margin-top: 15px;
            transition: all 0.3s ease;
        }

        .btn-continue-shopping:hover {
            background: var(--primary-color);
            color: white;
        }

        .empty-cart {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-cart-icon {
            font-size: 80px;
            color: #dee2e6;
            margin-bottom: 20px;
        }

        .empty-cart h3 {
            font-weight: 700;
            color: #6c757d;
            margin-bottom: 15px;
        }

        .empty-cart p {
            color: #6c757d;
            margin-bottom: 30px;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        .login-prompt {
            background: linear-gradient(135deg, #fff8e1, #fff3cd);
            border: 1px solid #ffecb5;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .login-prompt h4 {
            color: #856404;
            margin-bottom: 10px;
        }

        .login-prompt p {
            color: #856404;
            margin-bottom: 15px;
        }

        .stock-warning {
            font-size: 12px;
            color: var(--danger-color);
            margin-top: 5px;
        }

        .in-stock {
            color: var(--success-color);
            font-size: 14px;
            font-weight: 500;
        }

        .low-stock {
            color: var(--warning-color);
            font-size: 14px;
            font-weight: 500;
        }

        .out-of-stock {
            color: var(--danger-color);
            font-size: 14px;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .cart-item {
                flex-direction: column;
                text-align: center;
            }

            .cart-item-image {
                width: 100%;
                height: 200px;
                margin-bottom: 15px;
            }

            .cart-item-details {
                margin-bottom: 15px;
            }

            .cart-item-actions {
                flex-direction: column;
                gap: 15px;
                align-items: center;
            }

            .quantity-selector {
                margin-bottom: 10px;
            }

            .cart-summary {
                position: static;
                margin-top: 30px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="cart-page">
        <!-- Cart Header -->
        <div class="cart-header">
            <div class="container">
                <h1 class="fw-bold mb-3">Shopping Cart</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Home</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Cart</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="container">
            @if ($requiresLogin)
                <div class="login-prompt">
                    <h4><i class="fas fa-exclamation-circle me-2"></i> Login Required</h4>
                    <p>Please login as a customer to manage your cart items and proceed to checkout.</p>
                    <a href="{{ route('login') }}" class="btn btn-primary">
                        <i class="fas fa-sign-in-alt me-2"></i> Login Now
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-outline-primary ms-2">
                        <i class="fas fa-user-plus me-2"></i> Register
                    </a>
                </div>
            @endif

            @if ($cartItems && $cartItems->count() > 0)
                <div class="row">
                    <!-- Cart Items -->
                    <div class="col-lg-8">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="fw-bold mb-0">Your Cart Items ({{ $totalItems }})</h4>
                            <button class="btn btn-outline-danger btn-sm" onclick="clearCart()">
                                <i class="fas fa-trash-alt me-2"></i> Clear Cart
                            </button>
                        </div>

                        @foreach ($cartItems as $item)
                            <div class="cart-item" id="cart-item-{{ $item->id }}">
                                <div class="row align-items-center">
                                    <!-- Product Image -->
                                    <div class="col-md-3 mb-3 mb-md-0">
                                        <a href="{{ route('product.show', $item->product->slug) }}">
                                            @if ($item->product->images && $item->product->images->count() > 0)
                                                <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}"
                                                    alt="{{ $item->product->name }}" class="cart-item-image img-fluid">
                                            @else
                                                <img src="https://via.placeholder.com/300x200?text=No+Image"
                                                    alt="{{ $item->product->name }}" class="cart-item-image img-fluid">
                                            @endif
                                        </a>
                                    </div>

                                    <!-- Product Details -->
                                    <div class="col-md-4 mb-3 mb-md-0">
                                        <h5 class="cart-item-title">
                                            <a href="{{ route('product.show', $item->product->slug) }}">
                                                {{ $item->product->name }}
                                            </a>
                                        </h5>

                                        @if ($item->product->category)
                                            <p class="text-muted small mb-2">
                                                Category: {{ $item->product->category->name }}
                                            </p>
                                        @endif

                                        <!-- Stock Status -->
                                        @if ($item->product->stock_quantity > 10)
                                            <span class="in-stock">
                                                <i class="fas fa-check-circle me-1"></i> In Stock
                                            </span>
                                        @elseif($item->product->stock_quantity > 0)
                                            <span class="low-stock">
                                                <i class="fas fa-exclamation-triangle me-1"></i>
                                                Low Stock ({{ $item->product->stock_quantity }} left)
                                            </span>
                                        @else
                                            <span class="out-of-stock">
                                                <i class="fas fa-times-circle me-1"></i> Out of Stock
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Quantity & Price -->
                                    <div class="col-md-5">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="cart-item-actions">
                                                <!-- Quantity Selector -->
                                                <div class="quantity-selector">
                                                    <button class="quantity-btn"
                                                        onclick="updateQuantity({{ $item->id }}, 'decrease')"
                                                        {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                                        -
                                                    </button>
                                                    <input type="number" class="quantity-input"
                                                        value="{{ $item->quantity }}" min="1"
                                                        max="{{ $item->product->stock_quantity }}"
                                                        onchange="updateQuantityInput({{ $item->id }}, this.value)">
                                                    <button class="quantity-btn"
                                                        onclick="updateQuantity({{ $item->id }}, 'increase')"
                                                        {{ $item->quantity >= $item->product->stock_quantity ? 'disabled' : '' }}>
                                                        +
                                                    </button>
                                                </div>

                                                <!-- Remove Button -->
                                                <button class="remove-btn mt-2" onclick="removeItem({{ $item->id }})">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>

                                            <!-- Price -->
                                            <div class="text-end">
                                                <div class="cart-item-price">
                                                    ৳{{ number_format($item->price * $item->quantity, 2) }}
                                                </div>
                                                <div class="text-muted small">
                                                    ৳{{ number_format($item->price, 2) }} each
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Order Summary -->
                    <div class="col-lg-4">
                        <div class="cart-summary">
                            <h5 class="summary-title">Order Summary</h5>

                            <div class="summary-row">
                                <span class="summary-label">Subtotal ({{ $totalItems }} items)</span>
                                <span class="summary-value">৳{{ number_format($subtotal, 2) }}</span>
                            </div>

                            <div class="summary-row">
                                <span class="summary-label">Shipping</span>
                                <span class="summary-value">
                                    @if ($shipping == 0)
                                        <span class="text-success">Free</span>
                                    @else
                                        ৳{{ number_format($shipping, 2) }}
                                    @endif
                                </span>
                            </div>

                            <div class="summary-row">
                                <span class="summary-label">Tax</span>
                                <span class="summary-value">৳0.00</span>
                            </div>

                            <div class="summary-total">
                                <span>Total</span>
                                <span class="value">৳{{ number_format($total, 2) }}</span>
                            </div>

                            <p class="text-muted small mt-3 mb-0">
                                <i class="fas fa-info-circle me-1"></i>
                                All prices are in Bangladeshi Taka (৳)
                            </p>

                            <button class="btn-checkout" onclick="proceedToCheckout()"
                                {{ $requiresLogin ? 'disabled' : '' }}>
                                <i class="fas fa-lock me-2"></i> Proceed to Checkout
                            </button>

                            <a href="{{ route('shop') }}" class="btn-continue-shopping">
                                <i class="fas fa-arrow-left me-2"></i> Continue Shopping
                            </a>

                            <!-- Payment Methods -->
                            <div class="mt-4">
                                <p class="small text-muted mb-2">We accept:</p>
                                <div class="d-flex gap-2">
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
                </div>
            @else
                <!-- Empty Cart -->
                <div class="empty-cart">
                    <div class="empty-cart-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <h3>Your cart is empty</h3>
                    <p>Looks like you haven't added any products to your cart yet. Start shopping to add items to your cart.
                    </p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('shop') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-shopping-bag me-2"></i> Start Shopping
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-home me-2"></i> Go to Home
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Update quantity via buttons
        function updateQuantity(itemId, action) {
            const quantityInput = document.querySelector(`#cart-item-${itemId} .quantity-input`);
            let newQuantity = parseInt(quantityInput.value);

            if (action === 'increase') {
                newQuantity++;
            } else if (action === 'decrease') {
                newQuantity--;
            }

            if (newQuantity < 1) newQuantity = 1;
            if (newQuantity > parseInt(quantityInput.max)) newQuantity = parseInt(quantityInput.max);

            updateCartItem(itemId, newQuantity);
        }

        // Update quantity via input
        function updateQuantityInput(itemId, quantity) {
            const quantityInput = document.querySelector(`#cart-item-${itemId} .quantity-input`);
            let newQuantity = parseInt(quantity);

            if (newQuantity < 1) newQuantity = 1;
            if (newQuantity > parseInt(quantityInput.max)) newQuantity = parseInt(quantityInput.max);

            updateCartItem(itemId, newQuantity);
        }

        // AJAX update cart item
        function updateCartItem(itemId, quantity) {
            fetch(`/cart/update/${itemId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        quantity: quantity
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Toast.fire({
                            icon: 'success',
                            title: data.message
                        });
                        updateCartCount(data.cart_count);
                        location.reload(); // Reload to update prices
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: data.message || 'Failed to update cart'
                        });
                    }
                });
        }

        // Remove item from cart
        function removeItem(itemId) {
            if (!confirm('Are you sure you want to remove this item from your cart?')) {
                return;
            }

            fetch(`/cart/remove/${itemId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Toast.fire({
                            icon: 'success',
                            title: data.message
                        });
                        updateCartCount(data.cart_count);

                        // Remove item from DOM
                        document.getElementById(`cart-item-${itemId}`).remove();

                        // Check if cart is empty
                        if (document.querySelectorAll('.cart-item').length === 0) {
                            location.reload();
                        }
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: data.message || 'Failed to remove item'
                        });
                    }
                });
        }

        // Clear entire cart
        function clearCart() {
            if (!confirm('Are you sure you want to clear your entire cart?')) {
                return;
            }

            fetch('/cart/clear', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Toast.fire({
                            icon: 'success',
                            title: data.message
                        });
                        updateCartCount(data.cart_count);
                        location.reload();
                    }
                });
        }

        // Proceed to checkout
        function proceedToCheckout() {
            @if (Auth::check() && Auth::user()->role === 'customer')
                window.location.href = '{{ route('checkout.index') }}';
            @else
                const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
                loginModal.show();

                Toast.fire({
                    icon: 'warning',
                    title: 'Please login to proceed to checkout'
                });
            @endif
        }

        // Update cart count in header
        function updateCartCount(count) {
            const cartCountElement = document.querySelector('.cart-count');
            if (cartCountElement) {
                cartCountElement.textContent = count;
                if (count > 0) {
                    cartCountElement.style.display = 'flex';
                } else {
                    cartCountElement.style.display = 'none';
                }
            }
        }

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
    </script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

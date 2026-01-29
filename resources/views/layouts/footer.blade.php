<!-- Footer -->
<footer class="footer bg-dark text-white pt-5 pb-4">
    <div class="container">
        <div class="row g-4">
            <!-- Company Info -->
            <div class="col-lg-3 col-md-6">
                <h5 class="fw-bold mb-3">{{ config('app.name', 'Ecommerce BD') }}</h5>
                <p class="text-white-50 small">
                    Your trusted online shopping destination in Bangladesh. Quality products with fast shipping across
                    all 64 districts.
                </p>
                <div class="mt-3">
                    <a href="{{ route('about') }}"
                        class="text-white-50 text-decoration-none small d-block mb-2 hover-link">
                        <i class="fas fa-info-circle me-2"></i>About Us
                    </a>
                    <a href="{{ route('contact') }}"
                        class="text-white-50 text-decoration-none small d-block hover-link">
                        <i class="fas fa-envelope me-2"></i>Contact Us
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6">
                <h6 class="fw-bold mb-3">Shop</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="{{ route('shop') }}" class="text-white-50 text-decoration-none small hover-link">All
                            Products</a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('flash-sale') }}"
                            class="text-white-50 text-decoration-none small hover-link">Flash Sale</a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('new-arrivals') }}"
                            class="text-white-50 text-decoration-none small hover-link">New Arrivals</a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('offers') }}"
                            class="text-white-50 text-decoration-none small hover-link">Special Offers</a>
                    </li>
                    @auth
                        <li class="mb-2">
                            <a href="{{ route('wishlist.index') }}"
                                class="text-white-50 text-decoration-none small hover-link">My Wishlist</a>
                        </li>
                    @endauth
                </ul>
            </div>

            <!-- Customer Support -->
            <div class="col-lg-2 col-md-6">
                <h6 class="fw-bold mb-3">Support</h6>
                <ul class="list-unstyled">
                    @auth
                        @if (auth()->user()->role === 'customer')
                            <li class="mb-2">
                                <a href="{{ route('dashboard') }}"
                                    class="text-white-50 text-decoration-none small hover-link">My Account</a>
                            </li>
                        @endif
                    @endauth
                    <li class="mb-2">
                        <a href="{{ route('cart.index') }}"
                            class="text-white-50 text-decoration-none small hover-link">Shopping Cart</a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('terms') }}"
                            class="text-white-50 text-decoration-none small hover-link">Terms of Service</a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('privacy') }}"
                            class="text-white-50 text-decoration-none small hover-link">Privacy Policy</a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('contact') }}"
                            class="text-white-50 text-decoration-none small hover-link">Help Center</a>
                    </li>
                </ul>
            </div>

            <!-- Contact Information -->
            <div class="col-lg-2 col-md-6">
                <h6 class="fw-bold mb-3">Contact</h6>
                <ul class="list-unstyled text-white-50 small">
                    <li class="mb-2">
                        <i class="fas fa-phone me-2"></i>
                        <a href="tel:+8801234567890" class="text-white-50 text-decoration-none hover-link">+880
                            1234-567890</a>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-envelope me-2"></i>
                        <a href="mailto:{{ config('mail.from.address', 'support@ecommercebd.com') }}"
                            class="text-white-50 text-decoration-none hover-link">
                            {{ config('mail.from.address', 'support@ecommercebd.com') }}
                        </a>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        Dhaka, Bangladesh
                    </li>
                    <li class="mt-3">
                        <strong class="text-white">Business Hours:</strong><br>
                        Sat - Thu: 9 AM - 9 PM<br>
                        Friday: 3 PM - 9 PM
                    </li>
                </ul>
            </div>

            <!-- Newsletter & Social -->
            <div class="col-lg-3 col-md-12">
                <h6 class="fw-bold mb-3">Stay Connected</h6>
                <p class="text-white-50 small mb-3">Subscribe to our newsletter for exclusive deals and updates.</p>

                <form id="newsletterForm" class="mb-4">
                    @csrf
                    <div class="input-group input-group-sm">
                        <input type="email" class="form-control" placeholder="Your email" required>
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                    <div id="newsletterMessage" class="text-success small mt-2" style="display: none;">
                        Thank you for subscribing!
                    </div>
                </form>

                <h6 class="fw-bold mb-3">Follow Us</h6>
                <div class="d-flex gap-2 mb-3">
                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle"
                        style="width: 38px; height: 38px; padding: 0; display: flex; align-items: center; justify-content: center;"
                        aria-label="Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle"
                        style="width: 38px; height: 38px; padding: 0; display: flex; align-items: center; justify-content: center;"
                        aria-label="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle"
                        style="width: 38px; height: 38px; padding: 0; display: flex; align-items: center; justify-content: center;"
                        aria-label="YouTube">
                        <i class="fab fa-youtube"></i>
                    </a>
                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle"
                        style="width: 38px; height: 38px; padding: 0; display: flex; align-items: center; justify-content: center;"
                        aria-label="Twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Payment Methods -->
        <div class="row mt-4 pt-4 border-top border-secondary">
            <div class="col-12">
                <h6 class="fw-bold mb-3">We Accept</h6>
                <div class="d-flex flex-wrap align-items-center gap-3">
                    <span class="badge bg-light text-dark px-3 py-2">
                        <i class="fab fa-cc-visa fa-lg"></i> Visa
                    </span>
                    <span class="badge bg-light text-dark px-3 py-2">
                        <i class="fab fa-cc-mastercard fa-lg"></i> Mastercard
                    </span>
                    <span class="badge bg-light text-dark px-3 py-2">
                        <i class="fas fa-mobile-alt fa-lg"></i> bKash
                    </span>
                    <span class="badge bg-light text-dark px-3 py-2">
                        <i class="fas fa-mobile-alt fa-lg"></i> Nagad
                    </span>
                    <span class="badge bg-light text-dark px-3 py-2">
                        <i class="fas fa-money-bill-wave fa-lg"></i> Cash on Delivery
                    </span>
                </div>
            </div>
        </div>

        <!-- Copyright -->
        <div class="row mt-4 pt-3 border-top border-secondary">
            <div class="col-md-6 text-center text-md-start">
                <p class="text-white-50 small mb-0">
                    &copy; <span id="currentYear"></span> {{ config('app.name', 'Ecommerce BD') }}. All rights
                    reserved.
                </p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <p class="text-white-50 small mb-0">
                    <i class="fas fa-map-marked-alt me-1"></i> Serving all 64 districts of Bangladesh
                </p>
            </div>
        </div>
    </div>
</footer>

<!-- Footer Styles -->
<style>
    .footer {
        position: relative;
        margin-top: auto;
    }

    .footer .hover-link {
        transition: color 0.2s ease;
    }

    .footer .hover-link:hover {
        color: #fff !important;
    }

    .footer .btn-outline-light:hover {
        background-color: #fff;
        color: #212529;
    }

    .footer .input-group .btn-primary {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }

    .footer .badge {
        font-weight: 500;
        font-size: 0.85rem;
    }
</style>

<!-- Footer Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set current year
        document.getElementById('currentYear').textContent = new Date().getFullYear();

        // Newsletter form handling
        const newsletterForm = document.getElementById('newsletterForm');
        if (newsletterForm) {
            newsletterForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const emailInput = this.querySelector('input[type="email"]');
                const message = document.getElementById('newsletterMessage');

                if (emailInput.value) {
                    message.style.display = 'block';
                    emailInput.value = '';

                    setTimeout(() => {
                        message.style.display = 'none';
                    }, 5000);
                }
            });
        }
    });
</script>

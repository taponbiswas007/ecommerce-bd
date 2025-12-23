@extends('layouts.app')

@section('title', 'Contact Us - ElectroHub')
@section('description', 'Get in touch with ElectroHub. We are here to help with all your electronics needs.')

@section('content')
    <div class="container py-5">
        <!-- Page Header -->
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 mb-3">Contact Us</h1>
                <p class="lead text-muted">We're here to help with all your electronics needs</p>
                <div class="border-bottom border-primary border-3 mx-auto" style="width: 100px;"></div>
            </div>
        </div>

        <!-- Contact Information & Form -->
        <div class="row">
            <!-- Contact Information -->
            <div class="col-lg-4 mb-5 mb-lg-0">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h3 class="card-title mb-4">Get in Touch</h3>

                        <!-- Contact Details -->
                        <div class="mb-4">
                            <div class="d-flex align-items-start mb-3">
                                <div class="bg-primary bg-opacity-10 rounded p-2 me-3">
                                    <i class="fas fa-map-marker-alt text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Our Address</h6>
                                    <p class="text-muted mb-0">
                                        123 Tech Street<br>
                                        San Francisco, CA 94107<br>
                                        United States
                                    </p>
                                </div>
                            </div>

                            <div class="d-flex align-items-start mb-3">
                                <div class="bg-success bg-opacity-10 rounded p-2 me-3">
                                    <i class="fas fa-phone text-success"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Phone Number</h6>
                                    <p class="text-muted mb-0">
                                        <a href="tel:+14151234567" class="text-decoration-none">(415) 123-4567</a><br>
                                        Mon-Sat: 9AM-9PM, Sun: 10AM-6PM
                                    </p>
                                </div>
                            </div>

                            <div class="d-flex align-items-start mb-3">
                                <div class="bg-warning bg-opacity-10 rounded p-2 me-3">
                                    <i class="fas fa-envelope text-warning"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Email Address</h6>
                                    <p class="text-muted mb-0">
                                        <a href="mailto:info@electrohub.com"
                                            class="text-decoration-none">info@electrohub.com</a><br>
                                        <a href="mailto:support@electrohub.com"
                                            class="text-decoration-none">support@electrohub.com</a>
                                    </p>
                                </div>
                            </div>

                            <div class="d-flex align-items-start">
                                <div class="bg-info bg-opacity-10 rounded p-2 me-3">
                                    <i class="fas fa-clock text-info"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Business Hours</h6>
                                    <p class="text-muted mb-0">
                                        Monday - Friday: 9:00 AM - 8:00 PM<br>
                                        Saturday: 10:00 AM - 6:00 PM<br>
                                        Sunday: 11:00 AM - 5:00 PM
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Social Media -->
                        <div class="mt-4">
                            <h6 class="mb-3">Follow Us</h6>
                            <div class="d-flex gap-2">
                                <a href="#" class="btn btn-outline-primary btn-sm">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="#" class="btn btn-outline-info btn-sm">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="#" class="btn btn-outline-danger btn-sm">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <a href="#" class="btn btn-outline-success btn-sm">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                                <a href="#" class="btn btn-outline-primary btn-sm">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Live Chat -->
                        <div class="mt-4">
                            <div class="d-flex align-items-center bg-light rounded p-3">
                                <div class="bg-success rounded-circle p-2 me-3">
                                    <i class="fas fa-comments text-white"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Live Chat Support</h6>
                                    <p class="text-muted mb-0 small">Available 24/7</p>
                                </div>
                                <button class="btn btn-success btn-sm ms-auto">
                                    <i class="fas fa-comment-dots me-1"></i> Chat Now
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4 p-md-5">
                        <h3 class="card-title mb-4">Send us a Message</h3>

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                Please fix the following errors:
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('contact.submit') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Full Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                        id="phone" name="phone" value="{{ old('phone') }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="subject" class="form-label">Subject *</label>
                                    <select class="form-select @error('subject') is-invalid @enderror" id="subject"
                                        name="subject" required>
                                        <option value="" selected disabled>Select a subject</option>
                                        <option value="General Inquiry"
                                            {{ old('subject') == 'General Inquiry' ? 'selected' : '' }}>General Inquiry
                                        </option>
                                        <option value="Product Information"
                                            {{ old('subject') == 'Product Information' ? 'selected' : '' }}>Product
                                            Information</option>
                                        <option value="Order Status"
                                            {{ old('subject') == 'Order Status' ? 'selected' : '' }}>Order Status</option>
                                        <option value="Technical Support"
                                            {{ old('subject') == 'Technical Support' ? 'selected' : '' }}>Technical Support
                                        </option>
                                        <option value="Billing Question"
                                            {{ old('subject') == 'Billing Question' ? 'selected' : '' }}>Billing Question
                                        </option>
                                        <option value="Return/Exchange"
                                            {{ old('subject') == 'Return/Exchange' ? 'selected' : '' }}>Return/Exchange
                                        </option>
                                        <option value="Business Partnership"
                                            {{ old('subject') == 'Business Partnership' ? 'selected' : '' }}>Business
                                            Partnership</option>
                                        <option value="Other" {{ old('subject') == 'Other' ? 'selected' : '' }}>Other
                                        </option>
                                    </select>
                                    @error('subject')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="message" class="form-label">Message *</label>
                                <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="6"
                                    required>{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Please provide as much detail as possible so we can better assist
                                    you.</div>
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="newsletter" name="newsletter"
                                    {{ old('newsletter') ? 'checked' : '' }}>
                                <label class="form-check-label" for="newsletter">
                                    Subscribe to our newsletter for updates and promotions
                                </label>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-paper-plane me-2"></i> Send Message
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- FAQ Section -->
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-body p-4">
                        <h4 class="card-title mb-4">Frequently Asked Questions</h4>

                        <div class="accordion" id="contactFAQ">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#faq1">
                                        What are your response times?
                                    </button>
                                </h2>
                                <div id="faq1" class="accordion-collapse collapse show"
                                    data-bs-parent="#contactFAQ">
                                    <div class="accordion-body">
                                        We aim to respond to all inquiries within 24 hours. For urgent matters, please call
                                        our support line.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#faq2">
                                        Do you offer phone support?
                                    </button>
                                </h2>
                                <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#contactFAQ">
                                    <div class="accordion-body">
                                        Yes, our phone support is available during business hours. For after-hours support,
                                        please use email or live chat.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#faq3">
                                        Can I visit your physical store?
                                    </button>
                                </h2>
                                <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#contactFAQ">
                                    <div class="accordion-body">
                                        Yes, we have multiple retail locations. Please check our store locator for addresses
                                        and hours.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Map Section -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div class="p-4 border-bottom">
                            <h4 class="card-title mb-0">Find Our Store</h4>
                        </div>
                        <div id="map" style="height: 400px; background-color: #f8f9fa;">
                            <!-- Google Map would go here -->
                            <div class="h-100 d-flex align-items-center justify-content-center">
                                <div class="text-center">
                                    <i class="fas fa-map-marker-alt fa-3x text-muted mb-3"></i>
                                    <h5>Interactive Map</h5>
                                    <p class="text-muted">Map integration would show here</p>
                                    <a href="https://maps.google.com/?q=123+Tech+Street+San+Francisco+CA+94107"
                                        target="_blank" class="btn btn-primary">
                                        <i class="fas fa-external-link-alt me-2"></i> Open in Google Maps
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Emergency Contact -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="alert alert-warning">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="alert-heading mb-1">Need Immediate Assistance?</h5>
                            <p class="mb-0">For urgent technical support or order issues, call our emergency line:
                                <strong><a href="tel:+14151234567" class="text-decoration-none">(415)
                                        123-4567</a></strong>
                                (24/7 Support)
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .accordion-button:not(.collapsed) {
            background-color: rgba(var(--bs-primary-rgb), 0.1);
            color: var(--bs-primary);
        }
    </style>
@endsection

@extends('layouts.app')

@section('title', 'Our Services - ElectroHub')
@section('description', 'Discover the comprehensive services offered by ElectroHub for all your electronics needs.')

@section('content')
    <div class="container py-5">
        <!-- Page Header -->
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 mb-3">Our Services</h1>
                <p class="lead text-muted">Comprehensive Solutions for All Your Electronics Needs</p>
                <div class="border-bottom border-primary border-3 mx-auto" style="width: 100px;"></div>
            </div>
        </div>

        <!-- Main Services -->
        <div class="row mb-5">
            <div class="col-12 text-center mb-5">
                <h2 class="mb-3">What We Offer</h2>
                <p class="text-muted">From shopping to support, we've got you covered</p>
            </div>

            <!-- Service 1 -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 border shadow-sm hover-lift">
                    <div class="card-body p-4 text-center">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex p-4 mb-4">
                            <i class="fas fa-shopping-cart fa-3x text-primary"></i>
                        </div>
                        <h3 class="card-title mb-3">Easy Online Shopping</h3>
                        <p class="card-text text-muted mb-4">
                            Browse our extensive collection of electronics from the comfort of your home. User-friendly
                            interface with detailed product information and reviews.
                        </p>
                        <ul class="list-unstyled text-start">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Secure payment options</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Real-time stock updates</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Price comparison tools</li>
                            <li><i class="fas fa-check text-success me-2"></i> Wishlist and save for later</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Service 2 -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 border shadow-sm hover-lift">
                    <div class="card-body p-4 text-center">
                        <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex p-4 mb-4">
                            <i class="fas fa-truck fa-3x text-success"></i>
                        </div>
                        <h3 class="card-title mb-3">Fast & Reliable Delivery</h3>
                        <p class="card-text text-muted mb-4">
                            Get your electronics delivered quickly and safely. We partner with trusted carriers to ensure
                            your items arrive in perfect condition.
                        </p>
                        <ul class="list-unstyled text-start">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Same-day delivery in select
                                areas</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Free shipping on orders over
                                $99</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Real-time tracking</li>
                            <li><i class="fas fa-check text-success me-2"></i> Contactless delivery options</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Service 3 -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 border shadow-sm hover-lift">
                    <div class="card-body p-4 text-center">
                        <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex p-4 mb-4">
                            <i class="fas fa-headset fa-3x text-warning"></i>
                        </div>
                        <h3 class="card-title mb-3">Expert Customer Support</h3>
                        <p class="card-text text-muted mb-4">
                            Our knowledgeable support team is available 24/7 to help with product selection, technical
                            questions, and after-sales support.
                        </p>
                        <ul class="list-unstyled text-start">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> 24/7 live chat support</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Phone and email support</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Product setup assistance</li>
                            <li><i class="fas fa-check text-success me-2"></i> Troubleshooting guidance</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Service 4 -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 border shadow-sm hover-lift">
                    <div class="card-body p-4 text-center">
                        <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex p-4 mb-4">
                            <i class="fas fa-cogs fa-3x text-info"></i>
                        </div>
                        <h3 class="card-title mb-3">Installation & Setup</h3>
                        <p class="card-text text-muted mb-4">
                            Professional installation and setup services for complex electronics. Our certified technicians
                            ensure everything works perfectly.
                        </p>
                        <ul class="list-unstyled text-start">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Home theater setup</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Smart home installation</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Network configuration</li>
                            <li><i class="fas fa-check text-success me-2"></i> Device synchronization</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Service 5 -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 border shadow-sm hover-lift">
                    <div class="card-body p-4 text-center">
                        <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex p-4 mb-4">
                            <i class="fas fa-tools fa-3x text-danger"></i>
                        </div>
                        <h3 class="card-title mb-3">Repair & Maintenance</h3>
                        <p class="card-text text-muted mb-4">
                            Expert repair services for all major electronics brands. We use genuine parts and offer warranty
                            on all repairs.
                        </p>
                        <ul class="list-unstyled text-start">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Same-day repairs for common
                                issues</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Manufacturer-authorized
                                service center</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Free diagnostics</li>
                            <li><i class="fas fa-check text-success me-2"></i> Pickup and delivery service</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Service 6 -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 border shadow-sm hover-lift">
                    <div class="card-body p-4 text-center">
                        <div class="bg-purple bg-opacity-10 rounded-circle d-inline-flex p-4 mb-4">
                            <i class="fas fa-exchange-alt fa-3x" style="color: #7b2cbf;"></i>
                        </div>
                        <h3 class="card-title mb-3">Extended Warranty & Protection</h3>
                        <p class="card-text text-muted mb-4">
                            Protect your investment with our extended warranty plans. Comprehensive coverage against
                            accidental damage and mechanical failures.
                        </p>
                        <ul class="list-unstyled text-start">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Up to 3 years extended
                                warranty</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Accidental damage protection
                            </li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Theft protection options</li>
                            <li><i class="fas fa-check text-success me-2"></i> Quick replacement service</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Business Services -->
        <div class="row mb-5">
            <div class="col-12 text-center mb-5">
                <h2 class="mb-3">Business Solutions</h2>
                <p class="text-muted">Specialized services for corporate clients</p>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card h-100 border shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start">
                            <div class="bg-primary bg-opacity-10 rounded p-3 me-4">
                                <i class="fas fa-building fa-2x text-primary"></i>
                            </div>
                            <div>
                                <h4 class="card-title mb-3">Bulk Purchasing</h4>
                                <p class="card-text text-muted">
                                    Special pricing and dedicated account managers for businesses purchasing in bulk. Volume
                                    discounts and flexible payment terms available.
                                </p>
                                <a href="{{ route('contact') }}?service=bulk" class="btn btn-outline-primary">
                                    Request Quote <i class="fas fa-arrow-right ms-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card h-100 border shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start">
                            <div class="bg-success bg-opacity-10 rounded p-3 me-4">
                                <i class="fas fa-laptop-house fa-2x text-success"></i>
                            </div>
                            <div>
                                <h4 class="card-title mb-3">Corporate IT Solutions</h4>
                                <p class="card-text text-muted">
                                    Complete IT setup and management for offices. Hardware procurement, network setup, and
                                    ongoing IT support services.
                                </p>
                                <a href="{{ route('contact') }}?service=it" class="btn btn-outline-success">
                                    Learn More <i class="fas fa-arrow-right ms-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Service Process -->
        <div class="row mb-5">
            <div class="col-12 text-center mb-5">
                <h2 class="mb-3">How Our Service Works</h2>
                <p class="text-muted">Simple, transparent process from start to finish</p>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="text-center">
                    <div class="position-relative d-inline-block mb-3">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 60px; height: 60px; font-size: 1.5rem; font-weight: bold;">
                            1
                        </div>
                    </div>
                    <h5>Request Service</h5>
                    <p class="text-muted small">
                        Contact us via phone, chat, or online form. Provide details about your needs.
                    </p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="text-center">
                    <div class="position-relative d-inline-block mb-3">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 60px; height: 60px; font-size: 1.5rem; font-weight: bold;">
                            2
                        </div>
                    </div>
                    <h5>Consultation & Quote</h5>
                    <p class="text-muted small">
                        Our experts assess your needs and provide a detailed quote with timeline.
                    </p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="text-center">
                    <div class="position-relative d-inline-block mb-3">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 60px; height: 60px; font-size: 1.5rem; font-weight: bold;">
                            3
                        </div>
                    </div>
                    <h5>Service Execution</h5>
                    <p class="text-muted small">
                        Certified professionals perform the service with quality equipment and parts.
                    </p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="text-center">
                    <div class="position-relative d-inline-block mb-3">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 60px; height: 60px; font-size: 1.5rem; font-weight: bold;">
                            4
                        </div>
                    </div>
                    <h5>Quality Check & Support</h5>
                    <p class="text-muted small">
                        Final inspection and follow-up to ensure complete satisfaction.
                    </p>
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <div class="card border bg-gradient-primary text-white shadow-lg">
                    <div class="card-body p-5">
                        <h2 class="card-title mb-3">Need Our Services?</h2>
                        <p class="card-text mb-4">
                            Contact us today to discuss your electronics needs. Our team is ready to provide the perfect
                            solution for you.
                        </p>
                        <div class="d-flex flex-wrap justify-content-center gap-3">
                            <a href="{{ route('contact') }}" class="btn btn-light btn-lg">
                                <i class="fas fa-envelope me-2"></i> Contact Us
                            </a>
                            <a href="tel:+1234567890" class="btn btn-outline-light btn-lg">
                                <i class="fas fa-phone me-2"></i> Call Now
                            </a>
                        </div>
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

        .bg-purple {
            background-color: #7b2cbf !important;
        }
    </style>
@endsection

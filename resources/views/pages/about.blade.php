@extends('layouts.app')

@section('title', 'About Us - ElectroHub')
@section('description', 'Learn about ElectroHub - Your trusted partner for electronics and gadgets.')

@section('content')
    <div class="container py-5">
        <!-- Page Header -->
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 mb-3">About ElectroHub</h1>
                <p class="lead text-muted">Your Trusted Partner in Electronics Since 2010</p>
                <div class="border-bottom border-primary border-3 mx-auto" style="width: 100px;"></div>
            </div>
        </div>

        <!-- Company Story -->
        <div class="row mb-5 align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <img src="https://images.unsplash.com/photo-1552664730-d307ca884978" alt="ElectroHub Store"
                    class="img-fluid rounded-3 shadow-lg">
            </div>
            <div class="col-lg-6">
                <h2 class="mb-4">Our Story</h2>
                <p class="mb-4">Founded in 2010, ElectroHub started as a small electronics store with a big vision: to
                    make cutting-edge technology accessible to everyone. What began as a single retail location has grown
                    into a premier online destination for electronics enthusiasts.</p>
                <p class="mb-4">Over the years, we've expanded our product range from basic electronics to include the
                    latest smartphones, laptops, home appliances, and smart home devices. Our commitment to quality and
                    customer satisfaction has earned us the trust of over 500,000 customers nationwide.</p>
                <div class="d-flex align-items-center">
                    <div class="me-4">
                        <h3 class="text-primary mb-0">500K+</h3>
                        <p class="text-muted mb-0">Happy Customers</p>
                    </div>
                    <div class="me-4">
                        <h3 class="text-primary mb-0">10K+</h3>
                        <p class="text-muted mb-0">Products</p>
                    </div>
                    <div>
                        <h3 class="text-primary mb-0">13</h3>
                        <p class="text-muted mb-0">Years Experience</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mission & Vision -->
        <div class="row mb-5">
            <div class="col-md-6 mb-4">
                <div class="card h-100 border shadow-sm">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex p-3">
                                <i class="fas fa-bullseye fa-2x text-primary"></i>
                            </div>
                        </div>
                        <h3 class="card-title text-center mb-3">Our Mission</h3>
                        <p class="card-text text-center text-muted">
                            To provide innovative, high-quality electronics that enhance daily life while delivering
                            exceptional customer service and value.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card h-100 border shadow-sm">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex p-3">
                                <i class="fas fa-eye fa-2x text-success"></i>
                            </div>
                        </div>
                        <h3 class="card-title text-center mb-3">Our Vision</h3>
                        <p class="card-text text-center text-muted">
                            To become the leading online electronics retailer, recognized for innovation, reliability, and
                            customer-centric approach across the globe.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Our Values -->
        <div class="row mb-5">
            <div class="col-12 text-center mb-5">
                <h2 class="mb-3">Our Core Values</h2>
                <p class="text-muted">The principles that guide everything we do</p>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card h-100 border shadow-sm hover-lift">
                    <div class="card-body p-4 text-center">
                        <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex p-3 mb-3">
                            <i class="fas fa-award fa-2x text-info"></i>
                        </div>
                        <h4 class="card-title mb-3">Quality First</h4>
                        <p class="card-text text-muted">
                            We source only the best products from trusted manufacturers and conduct rigorous quality checks.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card h-100 border shadow-sm hover-lift">
                    <div class="card-body p-4 text-center">
                        <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex p-3 mb-3">
                            <i class="fas fa-users fa-2x text-warning"></i>
                        </div>
                        <h4 class="card-title mb-3">Customer Focus</h4>
                        <p class="card-text text-muted">
                            Our customers are at the heart of everything we do. We listen, understand, and exceed
                            expectations.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card h-100 border shadow-sm hover-lift">
                    <div class="card-body p-4 text-center">
                        <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex p-3 mb-3">
                            <i class="fas fa-lightbulb fa-2x text-success"></i>
                        </div>
                        <h4 class="card-title mb-3">Innovation</h4>
                        <p class="card-text text-muted">
                            We stay ahead of technology trends and continuously improve our services and product offerings.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Team Section -->
        <div class="row mb-5">
            <div class="col-12 text-center mb-5">
                <h2 class="mb-3">Meet Our Leadership Team</h2>
                <p class="text-muted">The passionate people behind ElectroHub</p>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card border shadow-sm hover-lift">
                    <div class="card-body text-center p-4">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d" alt="John Smith"
                            class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                        <h5 class="card-title mb-1">John Smith</h5>
                        <p class="text-muted mb-3">Founder & CEO</p>
                        <p class="card-text text-muted small">
                            15+ years in electronics industry. Passionate about making technology accessible to all.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card border shadow-sm hover-lift">
                    <div class="card-body text-center p-4">
                        <img src="https://images.unsplash.com/photo-1494790108755-2616b786d4d9" alt="Sarah Johnson"
                            class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                        <h5 class="card-title mb-1">Sarah Johnson</h5>
                        <p class="text-muted mb-3">Operations Director</p>
                        <p class="card-text text-muted small">
                            Expert in supply chain management and customer service operations.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card border shadow-sm hover-lift">
                    <div class="card-body text-center p-4">
                        <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e" alt="Michael Chen"
                            class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                        <h5 class="card-title mb-1">Michael Chen</h5>
                        <p class="text-muted mb-3">Technology Director</p>
                        <p class="card-text text-muted small">
                            Leads our tech innovation and ensures we stay at the forefront of electronics trends.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <div class="card border bg-gradient-primary text-white shadow-lg">
                    <div class="card-body p-5">
                        <h2 class="card-title mb-3">Join Our Journey</h2>
                        <p class="card-text mb-4">
                            Become part of our growing community of electronics enthusiasts. Whether you're looking for the
                            latest gadgets or expert advice, we're here for you.
                        </p>
                        <div class="d-flex flex-wrap justify-content-center gap-3">
                            <a href="{{ route('shop') }}" class="btn btn-light btn-lg">
                                <i class="fas fa-shopping-bag me-2"></i> Shop Now
                            </a>
                            <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg">
                                <i class="fas fa-envelope me-2"></i> Contact Us
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
    </style>
@endsection

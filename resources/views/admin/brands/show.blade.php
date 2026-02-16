@extends('admin.layouts.master')

@section('title', 'View Brand')
@section('page-title', $brand->name)
@section('page-subtitle', 'Brand details and products')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.brands.index') }}">Brands</a></li>
    <li class="breadcrumb-item active">{{ $brand->name }}</li>
@endsection

@section('page-actions')
    <a href="{{ route('admin.brands.edit', $brand) }}" class="btn btn-warning btn-sm me-2">
        <i class="fas fa-edit me-1"></i> Edit Brand
    </a>
    <a href="{{ route('admin.brands.index') }}" class="btn btn-light btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Back to List
    </a>
@endsection

@push('styles')
    <style>
        .brand-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 30px;
        }

        .brand-logo-large {
            width: 120px;
            height: 120px;
            object-fit: contain;
            background: white;
            border-radius: 12px;
            padding: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .stat-card {
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-size: 24px;
        }

        .product-card {
            transition: all 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
        }

        .social-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #f8f9fa;
            color: #495057;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .social-link:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .social-link.facebook:hover {
            background: #3b5998;
            color: white;
        }

        .social-link.twitter:hover {
            background: #1da1f2;
            color: white;
        }

        .social-link.instagram:hover {
            background: #e1306c;
            color: white;
        }

        .social-link.linkedin:hover {
            background: #0077b5;
            color: white;
        }
    </style>
@endpush

@section('content')
    <!-- Brand Header -->
    <div class="brand-header">
        <div class="row align-items-center">
            <div class="col-md-2 text-center">
                @if ($brand->logo)
                    <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" class="brand-logo-large">
                @else
                    <div class="brand-logo-large d-flex align-items-center justify-content-center">
                        <i class="fas fa-building fa-3x text-muted"></i>
                    </div>
                @endif
            </div>
            <div class="col-md-10">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h2 class="mb-2">{{ $brand->name }}</h2>
                        @if ($brand->country)
                            <p class="mb-2"><i class="fas fa-map-marker-alt me-2"></i>{{ $brand->country }}</p>
                        @endif
                        @if ($brand->founded_year)
                            <p class="mb-2"><i class="fas fa-calendar-alt me-2"></i>Since {{ $brand->founded_year }}</p>
                        @endif
                        @if ($brand->website)
                            <p class="mb-0">
                                <a href="{{ $brand->website }}" target="_blank" class="text-white">
                                    <i class="fas fa-globe me-2"></i>{{ $brand->website }}
                                </a>
                            </p>
                        @endif
                    </div>
                    <div>
                        @if ($brand->is_featured)
                            <span class="badge bg-warning text-dark px-3 py-2">
                                <i class="fas fa-star me-1"></i>Featured
                            </span>
                        @endif
                        @if ($brand->is_active)
                            <span class="badge bg-success px-3 py-2">
                                <i class="fas fa-check-circle me-1"></i>Active
                            </span>
                        @else
                            <span class="badge bg-danger px-3 py-2">
                                <i class="fas fa-times-circle me-1"></i>Inactive
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card bg-primary text-white">
                <div class="stat-icon bg-white text-primary">
                    <i class="fas fa-boxes"></i>
                </div>
                <h3 class="mb-0">{{ $brand->products_count ?? 0 }}</h3>
                <p class="mb-0">Total Products</p>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card bg-success text-white">
                <div class="stat-icon bg-white text-success">
                    <i class="fas fa-eye"></i>
                </div>
                <h3 class="mb-0">{{ number_format($brand->view_count ?? 0) }}</h3>
                <p class="mb-0">Total Views</p>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card bg-info text-white">
                <div class="stat-icon bg-white text-info">
                    <i class="fas fa-star"></i>
                </div>
                <h3 class="mb-0">
                    @php
                        $avgRating = $brand->products->avg('rating') ?? 0;
                    @endphp
                    {{ number_format($avgRating, 1) }}
                </h3>
                <p class="mb-0">Average Rating</p>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card bg-warning text-white">
                <div class="stat-icon bg-white text-warning">
                    <i class="fas fa-sort-amount-up"></i>
                </div>
                <h3 class="mb-0">{{ $brand->sort_order }}</h3>
                <p class="mb-0">Display Order</p>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Brand Information -->
        <div class="col-md-8">
            <!-- Description -->
            @if ($brand->description)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="fas fa-info-circle me-2"></i>About</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $brand->description }}</p>
                    </div>
                </div>
            @endif

            <!-- Products -->
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0"><i class="fas fa-shopping-bag me-2"></i>Products</h5>
                        <span class="badge bg-primary">{{ $brand->products->count() }}</span>
                    </div>
                </div>
                <div class="card-body">
                    @if ($brand->products->count() > 0)
                        <div class="row">
                            @foreach ($brand->products as $product)
                                <div class="col-md-4 col-sm-6 mb-3">
                                    <div class="card product-card">
                                        @if ($product->primaryImage)
                                            <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}"
                                                alt="{{ $product->name }}" class="product-image">
                                        @else
                                            <div
                                                class="product-image bg-light d-flex align-items-center justify-content-center">
                                                <i class="fas fa-image fa-3x text-muted"></i>
                                            </div>
                                        @endif
                                        <div class="card-body">
                                            <h6 class="mb-2">{{ $product->name }}</h6>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong
                                                        class="text-primary">৳{{ number_format($product->discount_price ?? $product->base_price, 2) }}</strong>
                                                    @if ($product->discount_price)
                                                        <br>
                                                        <small class="text-muted text-decoration-line-through">
                                                            ৳{{ number_format($product->base_price, 2) }}
                                                        </small>
                                                    @endif
                                                </div>
                                                <a href="{{ route('admin.products.show', $product) }}"
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No products available for this brand yet</p>
                            <a href="{{ route('admin.products.create', ['brand_id' => $brand->id]) }}"
                                class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Add Product
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Contact Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="fas fa-address-card me-2"></i>Contact Information</h5>
                </div>
                <div class="card-body">
                    @if ($brand->contact_email)
                        <div class="mb-3">
                            <strong><i class="fas fa-envelope me-2 text-primary"></i>Email</strong>
                            <p class="mb-0">{{ $brand->contact_email }}</p>
                        </div>
                    @endif

                    @if ($brand->contact_phone)
                        <div class="mb-3">
                            <strong><i class="fas fa-phone me-2 text-success"></i>Phone</strong>
                            <p class="mb-0">{{ $brand->contact_phone }}</p>
                        </div>
                    @endif

                    @if ($brand->contact_address)
                        <div class="mb-0">
                            <strong><i class="fas fa-map-marker-alt me-2 text-danger"></i>Address</strong>
                            <p class="mb-0">{{ $brand->contact_address }}</p>
                        </div>
                    @endif

                    @if (!$brand->contact_email && !$brand->contact_phone && !$brand->contact_address)
                        <p class="text-muted mb-0">No contact information available</p>
                    @endif
                </div>
            </div>

            <!-- Social Media -->
            @if ($brand->social_links && count(array_filter($brand->social_links)) > 0)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="fas fa-share-alt me-2"></i>Social Media</h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="d-flex justify-content-center gap-2">
                            @if (!empty($brand->social_links['facebook']))
                                <a href="{{ $brand->social_links['facebook'] }}" target="_blank"
                                    class="social-link facebook">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                            @endif
                            @if (!empty($brand->social_links['twitter']))
                                <a href="{{ $brand->social_links['twitter'] }}" target="_blank"
                                    class="social-link twitter">
                                    <i class="fab fa-twitter"></i>
                                </a>
                            @endif
                            @if (!empty($brand->social_links['instagram']))
                                <a href="{{ $brand->social_links['instagram'] }}" target="_blank"
                                    class="social-link instagram">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            @endif
                            @if (!empty($brand->social_links['linkedin']))
                                <a href="{{ $brand->social_links['linkedin'] }}" target="_blank"
                                    class="social-link linkedin">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Metadata -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="fas fa-info me-2"></i>Metadata</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3 pb-2 border-bottom">
                        <small class="text-muted">Created At</small>
                        <p class="mb-0">{{ $brand->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                    <div class="mb-3 pb-2 border-bottom">
                        <small class="text-muted">Updated At</small>
                        <p class="mb-0">{{ $brand->updated_at->format('M d, Y h:i A') }}</p>
                    </div>
                    @if ($brand->meta_title)
                        <div class="mb-3 pb-2 border-bottom">
                            <small class="text-muted">Meta Title</small>
                            <p class="mb-0">{{ $brand->meta_title }}</p>
                        </div>
                    @endif
                    @if ($brand->meta_description)
                        <div class="mb-0">
                            <small class="text-muted">Meta Description</small>
                            <p class="mb-0">{{ $brand->meta_description }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

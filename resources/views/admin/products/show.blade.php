@extends('admin.layouts.master')

@section('title', 'View Product')
@section('page-title', 'View Product')
@section('page-subtitle', 'Product details and information')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
    <li class="breadcrumb-item active">View</li>
@endsection

@push('styles')
    <style>
        .product-image-large {
            width: 100%;
            max-width: 400px;
            height: auto;
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }

        .image-thumbnail {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
            cursor: pointer;
            border: 2px solid transparent;
        }

        .image-thumbnail.active {
            border-color: #4361ee;
        }

        .badge-sm {
            font-size: 0.75em;
        }

        .info-card {
            border-left: 4px solid #4361ee;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Product Details</h5>
                    <div class="btn-group">
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i> Edit
                        </a>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-light">
                            <i class="fas fa-arrow-left me-2"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Product Images -->
                        <div class="col-md-5">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">Product Images</h6>
                                </div>
                                <div class="card-body">
                                    @if ($product->images->count() > 0)
                                        <!-- Main Image -->
                                        <div class="text-center mb-3">
                                            <img id="mainImage"
                                                src="{{ asset('storage/' . $product->featured_image->image_path) }}"
                                                alt="{{ $product->name }}" class="product-image-large">
                                        </div>

                                        <!-- Thumbnails -->
                                        <div class="d-flex flex-wrap gap-2 justify-content-center">
                                            @foreach ($product->images as $image)
                                                <img src="{{ asset('storage/' . $image->image_path) }}"
                                                    alt="{{ $product->name }}"
                                                    class="image-thumbnail {{ $loop->first ? 'active' : '' }}"
                                                    onclick="changeMainImage(this, '{{ asset('storage/' . $image->image_path) }}')">
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center py-4">
                                            <i class="fas fa-image fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">No images uploaded yet</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Quick Stats -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">Quick Stats</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-4">
                                            <div class="mb-2">
                                                <i class="fas fa-eye fa-2x text-info"></i>
                                            </div>
                                            <h5>{{ $product->view_count }}</h5>
                                            <small class="text-muted">Views</small>
                                        </div>
                                        <div class="col-4">
                                            <div class="mb-2">
                                                <i class="fas fa-shopping-cart fa-2x text-success"></i>
                                            </div>
                                            <h5>{{ $product->sold_count }}</h5>
                                            <small class="text-muted">Sold</small>
                                        </div>
                                        <div class="col-4">
                                            <div class="mb-2">
                                                <i class="fas fa-star fa-2x text-warning"></i>
                                            </div>
                                            <h5>{{ number_format($product->average_rating, 1) }}</h5>
                                            <small class="text-muted">Rating</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product Information -->
                        <div class="col-md-7">
                            <!-- Basic Info -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">Basic Information</h6>
                                </div>
                                <div class="card-body">
                                    <h3 class="mb-3">{{ $product->name }}</h3>

                                    <div class="mb-3">
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            @if ($product->is_featured)
                                                <span class="badge bg-warning">Featured</span>
                                            @endif
                                            @if ($product->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                            <span
                                                class="badge bg-info">{{ $product->category->name ?? 'No Category' }}</span>
                                        </div>

                                        <div class="d-flex align-items-center gap-3 mb-3">
                                            @if ($product->discount_price)
                                                <div>
                                                    <span class="text-muted text-decoration-line-through">
                                                        {{ config('app.currency_symbol') }}{{ number_format($product->base_price, 2) }}
                                                    </span>
                                                    <h4 class="text-danger d-inline ms-2">
                                                        {{ config('app.currency_symbol') }}{{ number_format($product->discount_price, 2) }}
                                                    </h4>
                                                    <span class="badge bg-danger ms-2">
                                                        {{ $product->discount_percentage }}% OFF
                                                    </span>
                                                </div>
                                            @else
                                                <h4 class="text-primary">
                                                    {{ config('app.currency_symbol') }}{{ number_format($product->base_price, 2) }}
                                                </h4>
                                            @endif
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="info-card p-3 mb-3">
                                                    <small class="text-muted d-block">SKU</small>
                                                    <strong>{{ $product->sku ?? 'N/A' }}</strong>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-card p-3 mb-3">
                                                    <small class="text-muted d-block">Unit</small>
                                                    <strong>{{ $product->unit->name ?? 'N/A' }}
                                                        ({{ $product->unit->short_code ?? '' }})</strong>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-card p-3 mb-3">
                                                    <small class="text-muted d-block">Stock Quantity</small>
                                                    <strong
                                                        class="{{ $product->stock_quantity > 0 ? 'text-success' : 'text-danger' }}">
                                                        {{ $product->stock_quantity }}
                                                        @if ($product->stock_quantity > 10)
                                                            <span class="badge bg-success badge-sm">In Stock</span>
                                                        @elseif($product->stock_quantity > 0)
                                                            <span class="badge bg-warning badge-sm">Low Stock</span>
                                                        @else
                                                            <span class="badge bg-danger badge-sm">Out of Stock</span>
                                                        @endif
                                                    </strong>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-card p-3 mb-3">
                                                    <small class="text-muted d-block">Min Order Quantity</small>
                                                    <strong>{{ $product->min_order_quantity }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if ($product->short_description)
                                        <div class="mb-3">
                                            <h6>Short Description</h6>
                                            <p class="text-muted">{{ $product->short_description }}</p>
                                        </div>
                                    @endif

                                    @if ($product->full_description)
                                        <div class="mb-3">
                                            <h6>Full Description</h6>
                                            <div class="text-muted">
                                                {!! $product->full_description !!}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Attributes -->
                            @if ($product->attributes)
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">Attributes</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            @php
                                                $attributes = json_decode($product->attributes, true);
                                            @endphp
                                            @if ($attributes && count($attributes) > 0)
                                                @foreach ($attributes as $key => $value)
                                                    <div class="col-md-6 mb-2">
                                                        <strong>{{ $key }}:</strong> {{ $value }}
                                                    </div>
                                                @endforeach
                                            @else
                                                <p class="text-muted">No attributes defined</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Shipping Information -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">Shipping Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @if ($product->weight)
                                            <div class="col-md-6 mb-3">
                                                <small class="text-muted d-block">Weight</small>
                                                <strong>{{ $product->weight }} kg</strong>
                                            </div>
                                        @endif
                                        @if ($product->dimensions)
                                            <div class="col-md-6 mb-3">
                                                <small class="text-muted d-block">Dimensions</small>
                                                <strong>{{ $product->dimensions }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Video -->
                            @if ($product->video_url)
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">Product Video</h6>
                                    </div>
                                    <div class="card-body">
                                        <a href="{{ $product->video_url }}" target="_blank"
                                            class="btn btn-outline-primary">
                                            <i class="fas fa-play me-2"></i> Watch Video
                                        </a>
                                        <small class="text-muted d-block mt-2">{{ $product->video_url }}</small>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <!-- SEO Information -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">SEO Information</h6>
                                </div>
                                <div class="card-body">
                                    @if ($product->meta_title)
                                        <div class="mb-3">
                                            <small class="text-muted d-block">Meta Title</small>
                                            <strong>{{ $product->meta_title }}</strong>
                                        </div>
                                    @endif
                                    @if ($product->meta_description)
                                        <div class="mb-3">
                                            <small class="text-muted d-block">Meta Description</small>
                                            <p class="mb-0">{{ $product->meta_description }}</p>
                                        </div>
                                    @endif
                                    @if ($product->meta_keywords)
                                        <div class="mb-3">
                                            <small class="text-muted d-block">Meta Keywords</small>
                                            <p class="mb-0">{{ $product->meta_keywords }}</p>
                                        </div>
                                    @endif
                                    @if (!$product->meta_title && !$product->meta_description && !$product->meta_keywords)
                                        <p class="text-muted">No SEO information provided</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Timeline</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <small class="text-muted d-block">Created At</small>
                                    <strong>{{ $product->created_at->format('M d, Y h:i A') }}</strong>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted d-block">Updated At</small>
                                    <strong>{{ $product->updated_at->format('M d, Y h:i A') }}</strong>
                                </div>
                                @if ($product->deleted_at)
                                    <div class="col-md-3">
                                        <small class="text-muted d-block">Deleted At</small>
                                        <strong
                                            class="text-danger">{{ $product->deleted_at->format('M d, Y h:i A') }}</strong>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function changeMainImage(thumbnail, imageUrl) {
            // Update main image
            document.getElementById('mainImage').src = imageUrl;

            // Update active thumbnail
            document.querySelectorAll('.image-thumbnail').forEach(img => {
                img.classList.remove('active');
            });
            thumbnail.classList.add('active');
        }
    </script>
@endpush

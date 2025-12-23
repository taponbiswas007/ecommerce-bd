@extends('admin.layouts.master')

@section('title', 'View Image')
@section('page-title', 'Image Details')
@section('page-subtitle', 'Product: ' . $product->name)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
    <li class="breadcrumb-item"><a
            href="{{ route('admin.products.show', $product->id) }}">{{ Str::limit($product->name, 20) }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.products.images.index', $product->id) }}">Images</a></li>
    <li class="breadcrumb-item active">View</li>
@endsection

@push('styles')
    <style>
        .image-container {
            text-align: center;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .main-image {
            max-width: 100%;
            height: auto;
            max-height: 500px;
            border-radius: 8px;
            border: 2px solid #dee2e6;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .badge-lg {
            font-size: 0.9em;
            padding: 6px 12px;
        }

        .info-card {
            border-left: 4px solid #4361ee;
            padding-left: 15px;
            margin-bottom: 15px;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Image Details</h5>
                    <div class="btn-group">
                        <a href="{{ route('admin.products.images.edit', ['product' => $product->id, 'image' => $image->id]) }}"
                            class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i> Edit
                        </a>
                        <a href="{{ route('admin.products.images.index', $product->id) }}" class="btn btn-light">
                            <i class="fas fa-arrow-left me-2"></i> Back to Images
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Image Display -->
                        <div class="col-md-6">
                            <div class="image-container">
                                <img src="{{ $image->image_url }}" alt="{{ $image->alt_text }}" class="main-image">

                                <!-- Badges -->
                                <div class="mt-3">
                                    @if ($image->is_primary)
                                        <span class="badge bg-success badge-lg me-2">
                                            <i class="fas fa-star me-1"></i> Primary Image
                                        </span>
                                    @endif
                                    @if ($image->is_featured)
                                        <span class="badge bg-warning badge-lg">
                                            <i class="fas fa-award me-1"></i> Featured Image
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Quick Stats -->
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">Quick Actions</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        @if (!$image->is_primary)
                                            <button class="btn btn-outline-success" onclick="setAsPrimary()">
                                                <i class="fas fa-star me-2"></i> Set as Primary
                                            </button>
                                        @endif
                                        @if (!$image->is_featured)
                                            <button class="btn btn-outline-warning" onclick="setAsFeatured()">
                                                <i class="fas fa-award me-2"></i> Set as Featured
                                            </button>
                                        @endif
                                        <a href="{{ $image->image_url }}" target="_blank" class="btn btn-outline-info">
                                            <i class="fas fa-external-link-alt me-2"></i> Open in New Tab
                                        </a>
                                        <button class="btn btn-outline-danger" onclick="confirmDelete()">
                                            <i class="fas fa-trash me-2"></i> Delete Image
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Image Information -->
                        <div class="col-md-6">
                            <!-- Basic Info -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">Basic Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="info-card">
                                        <small class="text-muted d-block">Alt Text</small>
                                        <h5>{{ $image->alt_text ?: 'No alt text provided' }}</h5>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="info-card">
                                                <small class="text-muted d-block">Display Order</small>
                                                <h4 class="text-primary">{{ $image->display_order }}</h4>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-card">
                                                <small class="text-muted d-block">Image ID</small>
                                                <strong>#{{ $image->id }}</strong>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="info-card">
                                        <small class="text-muted d-block">File Path</small>
                                        <code class="text-muted">{{ $image->image_path }}</code>
                                    </div>

                                    <div class="info-card">
                                        <small class="text-muted d-block">Full URL</small>
                                        <a href="{{ $image->image_url }}" target="_blank" class="text-truncate d-block">
                                            {{ $image->image_url }}
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Product Information -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">Product Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        @if ($product->featured_image)
                                            <img src="{{ $product->featured_image->image_url }}"
                                                alt="{{ $product->name }}"
                                                style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px; margin-right: 15px;">
                                        @endif
                                        <div>
                                            <h6 class="mb-0">{{ $product->name }}</h6>
                                            <small class="text-muted">Product ID: #{{ $product->id }}</small>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <small class="text-muted d-block">Category</small>
                                            <strong>{{ $product->category->name ?? 'N/A' }}</strong>
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted d-block">Price</small>
                                            <strong>{{ config('app.currency_symbol') }}{{ number_format($product->base_price, 2) }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Timestamps -->
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">Timestamps</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <small class="text-muted d-block">Created At</small>
                                            <strong>{{ $image->created_at->format('M d, Y h:i A') }}</strong>
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted d-block">Updated At</small>
                                            <strong>{{ $image->updated_at->format('M d, Y h:i A') }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Form (Hidden) -->
    <form id="deleteForm"
        action="{{ route('admin.products.images.destroy', ['product' => $product->id, 'image' => $image->id]) }}"
        method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('scripts')
    <script>
        async function setAsPrimary() {
            try {
                const response = await fetch(
                    `/admin/products/{{ $product->id }}/images/{{ $image->id }}/set-primary`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                const data = await response.json();

                if (data.success) {
                    Toast.fire({
                        icon: 'success',
                        title: 'Image set as primary successfully!'
                    });

                    // Reload after 1 second
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                }
            } catch (error) {
                console.error('Error:', error);
                Toast.fire({
                    icon: 'error',
                    title: 'Error setting as primary'
                });
            }
        }

        async function setAsFeatured() {
            try {
                const response = await fetch(
                    `/admin/products/{{ $product->id }}/images/{{ $image->id }}/set-featured`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                const data = await response.json();

                if (data.success) {
                    Toast.fire({
                        icon: 'success',
                        title: 'Image set as featured successfully!'
                    });

                    // Reload after 1 second
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                }
            } catch (error) {
                console.error('Error:', error);
                Toast.fire({
                    icon: 'error',
                    title: 'Error setting as featured'
                });
            }
        }

        function confirmDelete() {
            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to delete this image. This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteForm').submit();
                }
            });
        }
    </script>
@endpush

@extends('admin.layouts.master')

@section('title', 'Edit Image')
@section('page-title', 'Edit Image')
@section('page-subtitle', 'Update image details for: ' . $product->name)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
    <li class="breadcrumb-item"><a
            href="{{ route('admin.products.show', $product->id) }}">{{ Str::limit($product->name, 20) }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.products.images.index', $product->id) }}">Images</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@push('styles')
    <style>
        .image-preview {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            border: 2px solid #dee2e6;
        }

        .preview-container {
            text-align: center;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Edit Image Details</h5>
                </div>
                <div class="card-body">
                    <form
                        action="{{ route('admin.products.images.update', ['product' => $product->id, 'image' => $image->id]) }}"
                        method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-5">
                                <!-- Image Preview -->
                                <div class="preview-container mb-4">
                                    <img src="{{ $image->image_url }}" alt="{{ $image->alt_text }}"
                                        class="image-preview mb-3">
                                    <div class="text-muted">
                                        <small>
                                            <i class="fas fa-info-circle me-1"></i>
                                            Original: {{ basename($image->image_path) }}
                                        </small>
                                    </div>
                                </div>

                                <!-- Image Status -->
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">Image Status</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="is_primary"
                                                    name="is_primary" value="1"
                                                    {{ $image->is_primary ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_primary">
                                                    Set as Primary Image
                                                </label>
                                            </div>
                                            <small class="text-muted">
                                                Primary image is the main product image displayed in listings
                                            </small>
                                        </div>

                                        <div class="mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="is_featured"
                                                    name="is_featured" value="1"
                                                    {{ $image->is_featured ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_featured">
                                                    Set as Featured Image
                                                </label>
                                            </div>
                                            <small class="text-muted">
                                                Featured image might be used in featured product sections
                                            </small>
                                        </div>

                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Only one image can be primary or featured at a time.
                                            Enabling these will disable them for other images.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-7">
                                <!-- Image Details -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">Image Details</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="alt_text" class="form-label">Alt Text *</label>
                                            <input type="text"
                                                class="form-control @error('alt_text') is-invalid @enderror" id="alt_text"
                                                name="alt_text" value="{{ old('alt_text', $image->alt_text) }}" required>
                                            <small class="text-muted">
                                                Descriptive text for screen readers and SEO. Describe what the image shows.
                                            </small>
                                            @error('alt_text')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="display_order" class="form-label">Display Order</label>
                                            <input type="number"
                                                class="form-control @error('display_order') is-invalid @enderror"
                                                id="display_order" name="display_order"
                                                value="{{ old('display_order', $image->display_order) }}" min="0">
                                            <small class="text-muted">
                                                Lower numbers appear first. Images are sorted by this field.
                                            </small>
                                            @error('display_order')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Uploaded Information</label>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <small class="text-muted d-block">Uploaded On</small>
                                                    <strong>{{ $image->created_at->format('M d, Y h:i A') }}</strong>
                                                </div>
                                                <div class="col-md-6">
                                                    <small class="text-muted d-block">Last Updated</small>
                                                    <strong>{{ $image->updated_at->format('M d, Y h:i A') }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save me-2"></i> Update Image
                                            </button>
                                            <a href="{{ route('admin.products.images.index', $product->id) }}"
                                                class="btn btn-light">
                                                Cancel
                                            </a>
                                        </div>

                                        <hr>

                                        <div class="text-center">
                                            <button type="button" class="btn btn-outline-danger" onclick="confirmDelete()">
                                                <i class="fas fa-trash me-2"></i> Delete This Image
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
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

        // Handle primary/featured conflict
        document.addEventListener('DOMContentLoaded', function() {
            const primaryCheckbox = document.getElementById('is_primary');
            const featuredCheckbox = document.getElementById('is_featured');

            primaryCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    // Optional: Show confirmation
                    Swal.fire({
                        title: 'Set as Primary?',
                        text: 'This will remove primary status from the current primary image.',
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonText: 'Continue',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (!result.isConfirmed) {
                            this.checked = false;
                        }
                    });
                }
            });

            featuredCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    // Optional: Show confirmation
                    Swal.fire({
                        title: 'Set as Featured?',
                        text: 'This will remove featured status from the current featured image.',
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonText: 'Continue',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (!result.isConfirmed) {
                            this.checked = false;
                        }
                    });
                }
            });
        });
    </script>
@endpush

@extends('admin.layouts.master')

@section('title', 'Create Product')
@section('page-title', 'Create Product')
@section('page-subtitle', 'Add new product to your store')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
    <li class="breadcrumb-item active">Create</li>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css">
    <style>
        .image-preview-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }

        .image-preview {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
            border: 2px solid #dee2e6;
        }

        .remove-image {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 12px;
        }

        .attribute-row {
            margin-bottom: 10px;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data"
                        id="productForm">
                        @csrf

                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-md-8">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Basic Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Product Name *</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                id="name" name="name" value="{{ old('name') }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="category_id" class="form-label">Category *</label>
                                                    <select class="form-select @error('category_id') is-invalid @enderror"
                                                        id="category_id" name="category_id" required>
                                                        <option value="">Select Category</option>
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}"
                                                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                                {{ $category->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('category_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="unit_id" class="form-label">Unit *</label>
                                                    <select class="form-select @error('unit_id') is-invalid @enderror"
                                                        id="unit_id" name="unit_id" required>
                                                        <option value="">Select Unit</option>
                                                        @foreach ($units as $unit)
                                                            <option value="{{ $unit->id }}"
                                                                {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                                                {{ $unit->name }} ({{ $unit->short_code }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('unit_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="base_price" class="form-label">Base Price *</label>
                                                    <div class="input-group">
                                                        <span
                                                            class="input-group-text">{{ config('app.currency_symbol') }}</span>
                                                        <input type="number"
                                                            class="form-control @error('base_price') is-invalid @enderror"
                                                            id="base_price" name="base_price" step="0.01" min="0"
                                                            value="{{ old('base_price') }}" required>
                                                        @error('base_price')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="discount_price" class="form-label">Discount Price</label>
                                                    <div class="input-group">
                                                        <span
                                                            class="input-group-text">{{ config('app.currency_symbol') }}</span>
                                                        <input type="number"
                                                            class="form-control @error('discount_price') is-invalid @enderror"
                                                            id="discount_price" name="discount_price" step="0.01"
                                                            min="0" value="{{ old('discount_price') }}">
                                                        @error('discount_price')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <small class="text-muted">Leave empty for no discount</small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="short_description" class="form-label">Short Description</label>
                                            <textarea class="form-control @error('short_description') is-invalid @enderror" id="short_description"
                                                name="short_description" rows="3">{{ old('short_description') }}</textarea>
                                            <small class="text-muted">Brief description displayed in product listings (max
                                                500 characters)</small>
                                            @error('short_description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="full_description" class="form-label">Full Description</label>
                                            <textarea class="form-control @error('full_description') is-invalid @enderror" id="full_description"
                                                name="full_description">{{ old('full_description') }}</textarea>
                                            @error('full_description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Inventory -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Inventory</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="stock_quantity" class="form-label">Stock Quantity
                                                        *</label>
                                                    <input type="number"
                                                        class="form-control @error('stock_quantity') is-invalid @enderror"
                                                        id="stock_quantity" name="stock_quantity" min="0"
                                                        value="{{ old('stock_quantity', 0) }}" required>
                                                    @error('stock_quantity')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="min_order_quantity" class="form-label">Minimum Order
                                                        Quantity *</label>
                                                    <input type="number"
                                                        class="form-control @error('min_order_quantity') is-invalid @enderror"
                                                        id="min_order_quantity" name="min_order_quantity" min="1"
                                                        value="{{ old('min_order_quantity', 1) }}" required>
                                                    @error('min_order_quantity')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Attributes -->
                                <div class="card mb-4">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="card-title mb-0">Attributes</h5>
                                        <button type="button" class="btn btn-sm btn-primary" onclick="addAttribute()">
                                            <i class="fas fa-plus me-1"></i> Add Attribute
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        <div id="attributesContainer">
                                            <!-- Attributes will be added here dynamically -->
                                        </div>
                                        <small class="text-muted">Add custom attributes for this product (e.g., Color,
                                            Size, Material)</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Sidebar -->
                            <div class="col-md-4">
                                <!-- Status & Featured -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Publish</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <div class="form-check form-switch mb-3">
                                                <input class="form-check-input" type="checkbox" id="is_active"
                                                    name="is_active" value="1" checked>
                                                <label class="form-check-label" for="is_active">Active</label>
                                            </div>

                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="is_featured"
                                                    name="is_featured" value="1">
                                                <label class="form-check-label" for="is_featured">Featured Product</label>
                                            </div>
                                        </div>

                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save me-2"></i> Save Product
                                            </button>
                                            <a href="{{ route('admin.products.index') }}" class="btn btn-light">
                                                Cancel
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Shipping Information -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Shipping Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="weight" class="form-label">Weight (kg)</label>
                                            <input type="number"
                                                class="form-control @error('weight') is-invalid @enderror" id="weight"
                                                name="weight" step="0.01" min="0"
                                                value="{{ old('weight') }}">
                                            @error('weight')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="dimensions" class="form-label">Dimensions (L×W×H)</label>
                                            <input type="text"
                                                class="form-control @error('dimensions') is-invalid @enderror"
                                                id="dimensions" name="dimensions" placeholder="e.g., 10×5×2"
                                                value="{{ old('dimensions') }}">
                                            @error('dimensions')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Media -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Media</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="video_url" class="form-label">Video URL</label>
                                            <input type="url"
                                                class="form-control @error('video_url') is-invalid @enderror"
                                                id="video_url" name="video_url"
                                                placeholder="https://youtube.com/watch?v=..."
                                                value="{{ old('video_url') }}">
                                            @error('video_url')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">YouTube or Vimeo URL</small>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Product Images</label>
                                            <small class="text-muted d-block mb-2">(You'll add images after creating the
                                                product)</small>
                                            <div class="alert alert-info">
                                                <i class="fas fa-info-circle me-2"></i>
                                                You can add product images after saving the product.
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- SEO -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">SEO Settings</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="meta_title" class="form-label">Meta Title</label>
                                            <input type="text"
                                                class="form-control @error('meta_title') is-invalid @enderror"
                                                id="meta_title" name="meta_title" value="{{ old('meta_title') }}">
                                            <small class="text-muted">Recommended: 50-60 characters</small>
                                            @error('meta_title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="meta_description" class="form-label">Meta Description</label>
                                            <textarea class="form-control @error('meta_description') is-invalid @enderror" id="meta_description"
                                                name="meta_description" rows="3">{{ old('meta_description') }}</textarea>
                                            <small class="text-muted">Recommended: 150-160 characters</small>
                                            @error('meta_description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                            <input type="text"
                                                class="form-control @error('meta_keywords') is-invalid @enderror"
                                                id="meta_keywords" name="meta_keywords"
                                                value="{{ old('meta_keywords') }}">
                                            <small class="text-muted">Separate keywords with commas</small>
                                            @error('meta_keywords')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
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
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Summernote for description
            $('#full_description').summernote({
                height: 300,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });

            // Add attribute field
            window.addAttribute = function(key = '', value = '') {
                const container = document.getElementById('attributesContainer');
                const index = container.children.length;

                const row = document.createElement('div');
                row.className = 'row attribute-row';
                row.innerHTML = `
                    <div class="col-md-5">
                        <input type="text" class="form-control"
                            name="attributes[${index}][key]"
                            placeholder="Attribute name (e.g., Color)"
                            value="${key}">
                    </div>
                    <div class="col-md-5">
                        <input type="text" class="form-control"
                            name="attributes[${index}][value]"
                            placeholder="Attribute value (e.g., Red)"
                            value="${value}">
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger w-100" onclick="removeAttribute(this)">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
                container.appendChild(row);
            };

            // Remove attribute field
            window.removeAttribute = function(button) {
                button.closest('.attribute-row').remove();
            };

            // Add initial attribute field
            addAttribute();

            // Form validation
            document.getElementById('productForm').addEventListener('submit', function(e) {
                const basePrice = parseFloat(document.getElementById('base_price').value);
                const discountPrice = parseFloat(document.getElementById('discount_price').value);

                if (discountPrice && discountPrice >= basePrice) {
                    e.preventDefault();
                    alert('Discount price must be less than base price');
                    document.getElementById('discount_price').focus();
                }
            });

            // Auto-generate slug from name
            document.getElementById('name').addEventListener('blur', function() {
                const name = this.value.trim();
                if (name) {
                    // You can add slug generation here if needed
                }
            });
        });
    </script>
@endpush

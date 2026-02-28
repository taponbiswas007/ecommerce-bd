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
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
        @csrf

        <div class="row">
            <!-- Basic Information -->
            <div class="col-xl-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Basic Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Product Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Category *</label>
                                    <div class="input-group flex-nowrap border-1 rounded"
                                        style="border: var(--bs-border-width) solid var(--bs-border-color);">
                                        <select
                                            class="form-select searchable-select @error('category_id') is-invalid @enderror"
                                            id="category_id" name="category_id" required>
                                            <option value="">Select Category</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="button" class="btn btn-outline-primary text-nowrap"
                                            id="addCategoryBtn" data-bs-toggle="modal" data-bs-target="#addCategoryModal">+
                                            Add</button>
                                    </div>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="unit_id" class="form-label">Unit *</label>
                                    <div class="input-group flex-nowrap border-1 rounded"
                                        style="border: var(--bs-border-width) solid var(--bs-border-color);">
                                        <select class="form-select searchable-select @error('unit_id') is-invalid @enderror"
                                            id="unit_id" name="unit_id" required>
                                            <option value="">Select Unit</option>
                                            @foreach ($units as $unit)
                                                <option value="{{ $unit->id }}"
                                                    {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                                    {{ $unit->name }} ({{ $unit->short_code }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="button" class="btn btn-outline-primary text-nowrap" id="addUnitBtn"
                                            data-bs-toggle="modal" data-bs-target="#addUnitModal">+ Add</button>
                                    </div>
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
                                        <span class="input-group-text">{{ config('app.currency_symbol') }}</span>
                                        <input type="number" class="form-control @error('base_price') is-invalid @enderror"
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
                                        <span class="input-group-text">{{ config('app.currency_symbol') }}</span>
                                        <input type="number"
                                            class="form-control @error('discount_price') is-invalid @enderror"
                                            id="discount_price" name="discount_price" step="0.01" min="0"
                                            value="{{ old('discount_price') }}">
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
                        <button type="button" class="btn btn-sm btn-primary" onclick="addAttributeRow()">
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

                <!-- SEO -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">SEO Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="meta_title" class="form-label">Meta Title</label>
                            <input type="text" class="form-control @error('meta_title') is-invalid @enderror"
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
                            <input type="text" class="form-control @error('meta_keywords') is-invalid @enderror"
                                id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords') }}">
                            <small class="text-muted">Separate keywords with commas</small>
                            @error('meta_keywords')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Hidden template for attribute rows -->
                <template id="attributeTemplate">
                    <div class="row attribute-row mb-2">
                        <div class="col-md-5">
                            <input type="text" class="form-control attribute-key"
                                placeholder="Attribute name (e.g., Color)">
                        </div>
                        <div class="col-md-5">
                            <input type="text" class="form-control attribute-value"
                                placeholder="Attribute value (e.g., Red, Blue, Green)">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger w-100 btn-remove-attribute">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Sidebar -->
            <div class="col-xl-4">
                <!-- Status & Featured -->

                <div class="card mb-3">
                    <div class="card-header fw-bold">Deal of the Day</div>
                    <div class="card-body">

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_deal" value="1"
                                {{ old('is_deal', $product->is_deal ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label">Set as Deal of the Day</label>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deal End Time</label>
                            <input type="datetime-local" name="deal_end_at" class="form-control"
                                value="{{ old('deal_end_at', isset($product->deal_end_at) ? $product->deal_end_at->format('Y-m-d\TH:i') : '') }}">
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
                            <input type="number" class="form-control @error('weight') is-invalid @enderror"
                                id="weight" name="weight" step="0.01" min="0"
                                value="{{ old('weight') }}">
                            @error('weight')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="dimensions" class="form-label">Dimensions (L×W×H)</label>
                            <input type="text" class="form-control @error('dimensions') is-invalid @enderror"
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
                            <input type="url" class="form-control @error('video_url') is-invalid @enderror"
                                id="video_url" name="video_url" placeholder="https://youtube.com/watch?v=..."
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
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Publish</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                    value="1" checked>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>

                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured"
                                    value="1">
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

            </div>
        </div>
    </form>
@endsection

@push('scripts')
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

            // Add attribute field using template
            window.addAttributeRow = function() {
                const template = document.getElementById('attributeTemplate');
                const clone = template.content.cloneNode(true);
                const container = document.getElementById('attributesContainer');
                const row = clone.querySelector('.attribute-row');

                // Add remove event listener
                row.querySelector('.btn-remove-attribute').addEventListener('click', function(e) {
                    e.preventDefault();
                    this.closest('.attribute-row').remove();
                });

                container.appendChild(clone);
            };

            // Update form submission to properly serialize attributes
            document.getElementById('productForm').addEventListener('submit', function(e) {
                const basePrice = parseFloat(document.getElementById('base_price').value);
                const discountPrice = parseFloat(document.getElementById('discount_price').value);

                if (discountPrice && discountPrice >= basePrice) {
                    e.preventDefault();
                    alert('Discount price must be less than base price');
                    document.getElementById('discount_price').focus();
                    return;
                }

                // Before submitting, serialize attributes into hidden inputs
                const container = document.getElementById('attributesContainer');
                const rows = container.querySelectorAll('.attribute-row');
                const form = this;

                // Remove any existing attribute inputs
                document.querySelectorAll('input[name^="attributes"]').forEach(input => {
                    input.remove();
                });

                // Create proper inputs for each attribute
                rows.forEach((row, index) => {
                    const key = row.querySelector('.attribute-key').value;
                    const value = row.querySelector('.attribute-value').value;

                    if (key && value) {
                        // Create hidden inputs
                        const keyInput = document.createElement('input');
                        keyInput.type = 'hidden';
                        keyInput.name = `attributes[${index}][key]`;
                        keyInput.value = key;

                        const valueInput = document.createElement('input');
                        valueInput.type = 'hidden';
                        valueInput.name = `attributes[${index}][value]`;
                        valueInput.value = value;

                        form.appendChild(keyInput);
                        form.appendChild(valueInput);
                    }
                });

                // Allow form to submit normally
            });

            // Auto-generate slug from name
            document.getElementById('name').addEventListener('blur', function() {
                const name = this.value.trim();
                if (name) {
                    // You can add slug generation here if needed
                }
            });

            // Save new category
            $('#saveCategoryBtn').on('click', function() {
                var name = $('#newCategoryName').val().trim();
                if (!name) {
                    alert('Category name required');
                    return;
                }
                // AJAX to backend to save category
                $.post("{{ route('admin.categories.quickAdd') }}", {
                    name: name,
                    _token: '{{ csrf_token() }}'
                }, function(data) {
                    if (data.success && data.category) {
                        var option = new Option(data.category.name, data.category.id, true, true);
                        $('#category_id').append(option).trigger('change');
                        $('#addCategoryModal').modal('hide');
                        $('#newCategoryName').val('');
                    } else {
                        alert(data.message || 'Failed to add category');
                    }
                });
            });
        });
    </script>
@endpush

<!-- Add Unit Modal -->
<div class="modal fade" id="addUnitModal" tabindex="-1" aria-labelledby="addUnitModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUnitModalLabel">Add New Unit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="newUnitName" class="form-label">Unit Name</label>
                    <input type="text" class="form-control" id="newUnitName" placeholder="Enter unit name">
                </div>
                <div class="mb-3">
                    <label for="newUnitShortCode" class="form-label">Short Code</label>
                    <input type="text" class="form-control" id="newUnitShortCode" placeholder="Enter short code">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveUnitBtn">Save</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            // Save new unit
            $('#saveUnitBtn').on('click', function() {
                var name = $('#newUnitName').val().trim();
                var shortCode = $('#newUnitShortCode').val().trim();
                if (!name || !shortCode) {
                    alert('Unit name and short code required');
                    return;
                }
                // AJAX to backend to save unit
                $.post("{{ route('admin.units.quickAdd') }}", {
                    name: name,
                    short_code: shortCode,
                    _token: '{{ csrf_token() }}'
                }, function(data) {
                    if (data.success && data.unit) {
                        var option = new Option(data.unit.name + ' (' + data.unit.short_code + ')',
                            data.unit.id, true, true);
                        $('#unit_id').append(option).trigger('change');
                        $('#addUnitModal').modal('hide');
                        $('#newUnitName').val('');
                        $('#newUnitShortCode').val('');
                    } else {
                        alert(data.message || 'Failed to add unit');
                    }
                });
            });
        });
    </script>
@endpush

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCategoryModalLabel">Add New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="newCategoryName" class="form-label">Category Name</label>
                    <input type="text" class="form-control" id="newCategoryName"
                        placeholder="Enter category name">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveCategoryBtn">Save</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Enable Select2 for all searchable selects
            $('.searchable-select').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Select',
                allowClear: true
            });

            // Save new category
            $('#saveCategoryBtn').on('click', function() {
                var name = $('#newCategoryName').val().trim();
                if (!name) {
                    alert('Category name required');
                    return;
                }
                // AJAX to backend to save category
                $.post("{{ route('admin.categories.quickAdd') }}", {
                    name: name,
                    _token: '{{ csrf_token() }}'
                }, function(data) {
                    if (data.success && data.category) {
                        var option = new Option(data.category.name, data.category.id, true, true);
                        $('#category_id').append(option).trigger('change');
                        $('#addCategoryModal').modal('hide');
                        $('#newCategoryName').val('');
                    } else {
                        alert(data.message || 'Failed to add category');
                    }
                });
            });
        });
    </script>
@endpush

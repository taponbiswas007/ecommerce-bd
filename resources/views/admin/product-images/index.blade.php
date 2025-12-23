@extends('admin.layouts.master')

@section('title', 'Product Images')
@section('page-title', 'Product Images')
@section('page-subtitle', 'Manage images for: ' . $product->name)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
    <li class="breadcrumb-item"><a
            href="{{ route('admin.products.show', $product->id) }}">{{ Str::limit($product->name, 20) }}</a></li>
    <li class="breadcrumb-item active">Images</li>
@endsection

@section('page-actions')
    <a href="{{ route('admin.products.images.create', $product->id) }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i> Upload Images
    </a>
    <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-light ms-2">
        <i class="fas fa-arrow-left me-2"></i> Back to Product
    </a>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.css">
    <style>
        .image-card {
            border: 2px solid transparent;
            transition: all 0.3s;
            position: relative;
        }

        .image-card.primary {
            border-color: #28a745;
        }

        .image-card.featured {
            border-color: #ffc107;
        }

        .image-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .image-container {
            position: relative;
            width: 100%;
            height: 200px;
            overflow: hidden;
            background-color: #f8f9fa;
            border-radius: 5px 5px 0 0;
        }

        .image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }

        .image-container:hover img {
            transform: scale(1.05);
        }

        .image-badges {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 10;
        }

        .badge-sm {
            font-size: 0.65em;
            padding: 3px 6px;
        }

        .image-actions {
            position: absolute;
            top: 10px;
            right: 10px;
            opacity: 0;
            transition: opacity 0.3s;
            z-index: 10;
        }

        .image-card:hover .image-actions {
            opacity: 1;
        }

        .sortable-handle {
            cursor: move;
            color: #6c757d;
        }

        .sortable-handle:hover {
            color: #4361ee;
        }

        .image-checkbox {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 10;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Images for: {{ $product->name }}</h5>
                    <div class="card-tools">
                        <div class="badge bg-info">
                            {{ $images->count() }} {{ Str::plural('image', $images->count()) }}
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Bulk Actions -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="btn-group">
                                <button type="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown">
                                    Bulk Actions
                                </button>
                                <div class="dropdown-menu">
                                    <button class="dropdown-item" onclick="bulkAction('set_primary')">
                                        <i class="fas fa-star text-success me-2"></i> Set as Primary
                                    </button>
                                    <button class="dropdown-item" onclick="bulkAction('set_featured')">
                                        <i class="fas fa-award text-warning me-2"></i> Set as Featured
                                    </button>
                                    <div class="dropdown-divider"></div>
                                    <button class="dropdown-item text-danger" onclick="bulkAction('delete')">
                                        <i class="fas fa-trash me-2"></i> Delete
                                    </button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-light ms-2" onclick="saveOrder()">
                                <i class="fas fa-save me-2"></i> Save Order
                            </button>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="selectAll">
                                <label class="form-check-label" for="selectAll">Select All</label>
                            </div>
                        </div>
                    </div>

                    <!-- Images Grid -->
                    @if ($images->count() > 0)
                        <div class="row" id="sortable-images">
                            @foreach ($images as $image)
                                <div class="col-md-3 col-sm-6 mb-4" data-id="{{ $image->id }}">
                                    <div
                                        class="card image-card {{ $image->is_primary ? 'primary' : '' }} {{ $image->is_featured ? 'featured' : '' }}">
                                        <!-- Checkbox -->
                                        <div class="image-checkbox">
                                            <input type="checkbox" class="form-check-input image-checkbox-input"
                                                value="{{ $image->id }}">
                                        </div>

                                        <!-- Badges -->
                                        <div class="image-badges">
                                            @if ($image->is_primary)
                                                <span class="badge bg-success badge-sm">
                                                    <i class="fas fa-star"></i> Primary
                                                </span>
                                            @endif
                                            @if ($image->is_featured)
                                                <span class="badge bg-warning badge-sm">
                                                    <i class="fas fa-award"></i> Featured
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Actions -->
                                        <div class="image-actions">
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-info" onclick="viewImage({{ $image->id }})"
                                                    title="View">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-primary"
                                                    onclick="window.location.href='{{ route('admin.products.images.edit', ['product' => $product->id, 'image' => $image->id]) }}'"
                                                    title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-danger confirm-delete" data-id="{{ $image->id }}"
                                                    data-name="{{ $image->alt_text ?: 'Image #' . $image->id }}"
                                                    title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Image -->
                                        <div class="image-container">
                                            <img src="{{ $image->image_url }}" alt="{{ $image->alt_text }}"
                                                class="img-fluid" loading="lazy">
                                        </div>

                                        <!-- Card Body -->
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="mb-1" title="{{ $image->alt_text }}">
                                                        {{ Str::limit($image->alt_text, 20) ?: 'Image' }}
                                                    </h6>
                                                    <small class="text-muted">
                                                        <i class="fas fa-sort me-1"></i>
                                                        Order:
                                                        <input type="number"
                                                            class="form-control form-control-sm d-inline-block order-input"
                                                            value="{{ $image->display_order }}" style="width: 70px;"
                                                            min="0" data-id="{{ $image->id }}">
                                                    </small>
                                                </div>
                                                <div class="sortable-handle">
                                                    <i class="fas fa-bars"></i>
                                                </div>
                                            </div>

                                            <!-- Quick Actions -->
                                            <div class="d-flex gap-2 mt-2">
                                                @if (!$image->is_primary)
                                                    <button class="btn btn-sm btn-outline-success w-100"
                                                        onclick="setPrimary({{ $image->id }})" title="Set as Primary">
                                                        <i class="fas fa-star"></i>
                                                    </button>
                                                @endif
                                                @if (!$image->is_featured)
                                                    <button class="btn btn-sm btn-outline-warning w-100"
                                                        onclick="setFeatured({{ $image->id }})"
                                                        title="Set as Featured">
                                                        <i class="fas fa-award"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-images fa-4x text-muted mb-3"></i>
                            <h4>No Images Found</h4>
                            <p class="text-muted">This product doesn't have any images yet.</p>
                            <a href="{{ route('admin.products.images.create', $product->id) }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i> Upload Images
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete <strong id="deleteImageName"></strong>?</p>
                    <p class="text-danger"><small>This action cannot be undone.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- View Modal -->
    <div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Image Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" alt="" class="img-fluid" style="max-height: 500px;">
                    <div id="imageInfo" class="mt-3"></div>
                </div>
                <div class="modal-footer">
                    <a href="#" id="editLink" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i> Edit
                    </a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Action Form -->
    <form id="bulkActionForm" method="POST" action="{{ route('admin.products.images.bulk-action', $product->id) }}">
        @csrf
        <input type="hidden" name="action" id="bulkAction">
        <input type="hidden" name="ids" id="bulkIds">
        <input type="hidden" name="display_order" id="bulkDisplayOrder">
    </form>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Sortable
            const sortableContainer = document.getElementById('sortable-images');
            if (sortableContainer) {
                new Sortable(sortableContainer, {
                    animation: 150,
                    handle: '.sortable-handle',
                    ghostClass: 'sortable-ghost',
                    onEnd: function() {
                        updateOrderNumbers();
                    }
                });
            }

            // Update order numbers
            function updateOrderNumbers() {
                const items = document.querySelectorAll('#sortable-images > div');
                items.forEach((item, index) => {
                    const orderInput = item.querySelector('.order-input');
                    if (orderInput) {
                        orderInput.value = index + 1;
                    }
                });
            }

            // Select all checkboxes
            document.getElementById('selectAll')?.addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('.image-checkbox-input');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });

            // Individual checkbox changes
            document.querySelectorAll('.image-checkbox-input').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    updateSelectAllCheckbox();
                });
            });

            function updateSelectAllCheckbox() {
                const checkboxes = document.querySelectorAll('.image-checkbox-input');
                const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                const selectAll = document.getElementById('selectAll');
                if (selectAll) {
                    selectAll.checked = allChecked;
                }
            }

            // Delete confirmation
            document.querySelectorAll('.confirm-delete').forEach(button => {
                button.addEventListener('click', function() {
                    const imageId = this.getAttribute('data-id');
                    const imageName = this.getAttribute('data-name');

                    document.getElementById('deleteImageName').textContent = imageName;
                    document.getElementById('deleteForm').action =
                        `/admin/products/{{ $product->id }}/images/${imageId}`;

                    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
                    deleteModal.show();
                });
            });

            // View image in modal
            window.viewImage = function(imageId) {
                // Fetch image details via AJAX or use data attributes
                const card = document.querySelector(`[data-id="${imageId}"]`);
                if (card) {
                    const img = card.querySelector('img');
                    const altText = card.querySelector('h6').title;

                    document.getElementById('modalImage').src = img.src;
                    document.getElementById('modalImage').alt = altText;

                    // Set edit link
                    document.getElementById('editLink').href =
                        `/admin/products/{{ $product->id }}/images/${imageId}/edit`;

                    const viewModal = new bootstrap.Modal(document.getElementById('viewModal'));
                    viewModal.show();
                }
            };
        });

        // Save order
        function saveOrder() {
            const orderData = {};
            document.querySelectorAll('.order-input').forEach(input => {
                orderData[input.getAttribute('data-id')] = input.value;
            });

            document.getElementById('bulkDisplayOrder').value = JSON.stringify(orderData);
            document.getElementById('bulkAction').value = 'update_order';
            document.getElementById('bulkActionForm').submit();
        }

        // Bulk actions
        function bulkAction(action) {
            const selectedIds = [];
            document.querySelectorAll('.image-checkbox-input:checked').forEach(checkbox => {
                selectedIds.push(checkbox.value);
            });

            if (selectedIds.length === 0) {
                Toast.fire({
                    icon: 'warning',
                    title: 'Please select at least one image'
                });
                return;
            }

            if (action === 'delete') {
                Swal.fire({
                    title: 'Are you sure?',
                    text: `This will delete ${selectedIds.length} image(s). This action cannot be undone!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete them!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        performBulkAction(action, selectedIds);
                    }
                });
            } else {
                performBulkAction(action, selectedIds);
            }
        }

        function performBulkAction(action, ids) {
            document.getElementById('bulkAction').value = action;
            document.getElementById('bulkIds').value = JSON.stringify(ids);
            document.getElementById('bulkActionForm').submit();
        }

        // Set as primary
        async function setPrimary(imageId) {
            try {
                const response = await fetch(`/admin/products/{{ $product->id }}/images/${imageId}/set-primary`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    // Update UI
                    document.querySelectorAll('.image-card').forEach(card => {
                        card.classList.remove('primary');
                    });

                    const card = document.querySelector(`[data-id="${imageId}"] .image-card`);
                    if (card) {
                        card.classList.add('primary');
                    }

                    Toast.fire({
                        icon: 'success',
                        title: data.message
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
                    title: 'Error setting primary image'
                });
            }
        }

        // Set as featured
        async function setFeatured(imageId) {
            try {
                const response = await fetch(`/admin/products/{{ $product->id }}/images/${imageId}/set-featured`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    // Update UI
                    document.querySelectorAll('.image-card').forEach(card => {
                        card.classList.remove('featured');
                    });

                    const card = document.querySelector(`[data-id="${imageId}"] .image-card`);
                    if (card) {
                        card.classList.add('featured');
                    }

                    Toast.fire({
                        icon: 'success',
                        title: data.message
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
                    title: 'Error setting featured image'
                });
            }
        }

        // Update order via AJAX (optional)
        async function updateOrderAjax() {
            const items = [];
            document.querySelectorAll('#sortable-images > div').forEach((item, index) => {
                const imageId = item.getAttribute('data-id');
                items.push({
                    id: imageId,
                    order: index + 1
                });
            });

            try {
                const response = await fetch(`/admin/products/{{ $product->id }}/images/reorder`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        images: items
                    })
                });

                const data = await response.json();

                if (data.success) {
                    Toast.fire({
                        icon: 'success',
                        title: 'Order saved successfully!'
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                Toast.fire({
                    icon: 'error',
                    title: 'Error saving order'
                });
            }
        }
    </script>
@endpush

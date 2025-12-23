@extends('admin.layouts.master')

@section('title', 'Edit Unit')
@section('page-title', 'Edit Unit: ' . $unit->name)
@section('page-subtitle', 'Update unit information')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.units.index') }}">Units</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Edit Unit</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.units.update', $unit->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Unit Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name', $unit->name) }}" required placeholder="e.g., Kilogram">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="symbol" class="form-label">Symbol *</label>
                            <input type="text" class="form-control @error('symbol') is-invalid @enderror" id="symbol"
                                name="symbol" value="{{ old('symbol', $unit->symbol) }}" required placeholder="e.g., kg"
                                maxlength="10">
                            @error('symbol')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Short symbol for the unit (max 10 characters)</small>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                rows="3" placeholder="Optional description for the unit">{{ old('description', $unit->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                    value="1" {{ old('is_active', $unit->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>
                            <small class="text-muted">Inactive units won't be available for new products</small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.units.index') }}" class="btn btn-light">
                                <i class="fas fa-arrow-left me-2"></i> Back to List
                            </a>
                            <div>
                                <a href="{{ route('admin.units.show', $unit->id) }}" class="btn btn-info me-2">
                                    <i class="fas fa-eye me-2"></i> View
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i> Update Unit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <!-- Unit Info -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Unit Details</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th width="120">Full Name:</th>
                            <td>{{ $unit->name }} ({{ $unit->symbol }})</td>
                        </tr>
                        <tr>
                            <th>Created:</th>
                            <td>{{ $unit->created_at->format('M d, Y') }}</td>
                        </tr>
                        <tr>
                            <th>Updated:</th>
                            <td>{{ $unit->updated_at->format('M d, Y') }}</td>
                        </tr>
                        <tr>
                            <th>Products:</th>
                            <td>
                                <span class="badge bg-primary">
                                    {{ $unit->products_count }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                <span class="badge bg-{{ $unit->is_active ? 'success' : 'danger' }}">
                                    {{ $unit->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                        </tr>
                    </table>

                    @if ($unit->description)
                        <div class="mt-3">
                            <label class="form-label">Description</label>
                            <p class="text-muted">{{ $unit->description }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="card border-danger mt-4">
                <div class="card-header bg-danger text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-exclamation-triangle me-2"></i> Danger Zone</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">
                        @if ($unit->products_count > 0)
                            <i class="fas fa-exclamation-circle text-warning me-1"></i>
                            This unit has {{ $unit->products_count }} associated products. You cannot delete it until all
                            products are reassigned to another unit.
                        @else
                            Once you delete a unit, there is no going back. Please be certain.
                        @endif
                    </p>

                    <form action="{{ route('admin.units.destroy', $unit->id) }}" method="POST" id="deleteForm">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-outline-danger w-100 confirm-delete"
                            data-name="{{ $unit->name }}" {{ $unit->products_count > 0 ? 'disabled' : '' }}>
                            <i class="fas fa-trash me-2"></i>
                            {{ $unit->products_count > 0 ? 'Cannot Delete (Has Products)' : 'Delete This Unit' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Delete confirmation
            const deleteButton = document.querySelector('.confirm-delete');
            if (deleteButton && !deleteButton.disabled) {
                deleteButton.addEventListener('click', function() {
                    const unitName = this.getAttribute('data-name');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: `You are about to delete "${unitName}". This action cannot be undone!`,
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
                });
            }
        });
    </script>
@endpush

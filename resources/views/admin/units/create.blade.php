@extends('admin.layouts.master')

@section('title', 'Create Unit')
@section('page-title', 'Create New Unit')
@section('page-subtitle', 'Add a new measurement unit')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.units.index') }}">Units</a></li>
    <li class="breadcrumb-item active">Create</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Unit Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.units.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Unit Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name') }}" required placeholder="e.g., Kilogram">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="symbol" class="form-label">Symbol *</label>
                            <input type="text" class="form-control @error('symbol') is-invalid @enderror" id="symbol"
                                name="symbol" value="{{ old('symbol') }}" required placeholder="e.g., kg" maxlength="10">
                            @error('symbol')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Short symbol for the unit (max 10 characters)</small>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                rows="3" placeholder="Optional description for the unit">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                    value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>
                            <small class="text-muted">Inactive units won't be available for new products</small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.units.index') }}" class="btn btn-light">
                                <i class="fas fa-arrow-left me-2"></i> Back to List
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Create Unit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <!-- Common Units -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="fas fa-lightbulb me-2"></i> Common Units</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Unit</th>
                                    <th>Symbol</th>
                                    <th>Used For</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Kilogram</td>
                                    <td><span class="badge bg-primary">kg</span></td>
                                    <td>Weight</td>
                                </tr>
                                <tr>
                                    <td>Gram</td>
                                    <td><span class="badge bg-primary">g</span></td>
                                    <td>Small weight</td>
                                </tr>
                                <tr>
                                    <td>Piece</td>
                                    <td><span class="badge bg-primary">pc</span></td>
                                    <td>Countable items</td>
                                </tr>
                                <tr>
                                    <td>Meter</td>
                                    <td><span class="badge bg-primary">m</span></td>
                                    <td>Length</td>
                                </tr>
                                <tr>
                                    <td>Liter</td>
                                    <td><span class="badge bg-primary">L</span></td>
                                    <td>Liquid volume</td>
                                </tr>
                                <tr>
                                    <td>Dozen</td>
                                    <td><span class="badge bg-primary">doz</span></td>
                                    <td>12 pieces</td>
                                </tr>
                                <tr>
                                    <td>Box</td>
                                    <td><span class="badge bg-primary">box</span></td>
                                    <td>Packaged items</td>
                                </tr>
                                <tr>
                                    <td>Carton</td>
                                    <td><span class="badge bg-primary">ctn</span></td>
                                    <td>Large packaging</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Stats -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Units Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="bg-primary text-white rounded-circle mx-auto d-flex align-items-center justify-content-center"
                                style="width: 60px; height: 60px;">
                                <i class="fas fa-balance-scale fa-2x"></i>
                            </div>
                            <h4 class="mt-2 mb-0">{{ \App\Models\Unit::count() }}</h4>
                            <small class="text-muted">Total Units</small>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="bg-success text-white rounded-circle mx-auto d-flex align-items-center justify-content-center"
                                style="width: 60px; height: 60px;">
                                <i class="fas fa-check-circle fa-2x"></i>
                            </div>
                            <h4 class="mt-2 mb-0">{{ \App\Models\Unit::where('is_active', true)->count() }}</h4>
                            <small class="text-muted">Active Units</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('admin.layouts.master')

@section('title', 'VAT & AIT Settings')

@section('content')
    <div class="container-fluid py-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>There were some issues:</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h1 class="h3 mb-0">
                    <i class="fas fa-percent text-primary me-2"></i>VAT & AIT Management
                </h1>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('admin.vat-ait.history') }}" class="btn btn-info btn-sm">
                    <i class="fas fa-history me-1"></i>History
                </a>
                <a href="{{ route('admin.vat-ait.report') }}" class="btn btn-info btn-sm">
                    <i class="fas fa-chart-bar me-1"></i>Report
                </a>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs mb-4" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="global-tab" data-bs-toggle="tab" href="#global" role="tab">
                    <i class="fas fa-cog me-2"></i>Global Settings
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="products-tab" data-bs-toggle="tab" href="#products" role="tab">
                    <i class="fas fa-box me-2"></i>Product Overrides
                </a>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content">
            <!-- Global Settings Tab -->
            <div class="tab-pane fade show active" id="global" role="tabpanel">
                @include('admin.vat-ait.partials.global-settings')
            </div>

            <!-- Product Overrides Tab -->
            <div class="tab-pane fade" id="products" role="tabpanel">
                @include('admin.vat-ait.partials.product-taxes-inline')
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .nav-tabs .nav-link {
            color: #495057;
            border: 1px solid transparent;
            border-bottom: 3px solid transparent;
        }

        .nav-tabs .nav-link.active {
            color: #0d6efd;
            border-color: transparent;
            border-bottom-color: #0d6efd;
            background-color: transparent;
        }

        .nav-tabs .nav-link:hover {
            border-color: #dee2e6;
        }
    </style>
@endsection

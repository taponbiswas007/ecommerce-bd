@extends('layouts.app')

@section('title', 'Terms & Conditions - ElectroHub')
@section('description', 'Read ElectroHub terms and conditions for using our website and services.')

@section('content')
    <div class="container-fluid py-5">
        <!-- Page Header -->
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 mb-3">Terms & Conditions</h1>
                <p class="lead text-muted">
                    @if ($terms)
                        Last Updated: {{ $terms->updated_at->format('F d, Y') }}
                    @else
                        Our terms and conditions
                    @endif
                </p>
                <div class="border-bottom border-primary border-3 mx-auto" style="width: 100px;"></div>
            </div>
        </div>

        <!-- Terms Content -->
        <div class="row">
            <div class="col-12 mx-auto">
                <div class="card border shadow-sm">
                    <div class="card-body p-4 p-md-5">
                        @if ($terms)
                            <h2 class="mb-4">{{ $terms->title }}</h2>
                            <div class="terms-content">
                                {!! $terms->content !!}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-file-contract fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Terms & Conditions are being updated. Please check back soon.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="row mt-4">
                    <div class="col-md-6 mb-3">
                        <div class="card border shadow-sm">
                            <div class="card-body p-4">
                                <h5 class="card-title mb-3">
                                    <i class="fas fa-shield-alt text-primary me-2"></i> Privacy Policy
                                </h5>
                                <p class="card-text text-muted small">
                                    Learn how we collect, use, and protect your personal information.
                                </p>
                                <a href="{{ route('privacy') }}" class="btn btn-outline-primary btn-sm">
                                    Read Privacy Policy
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="card border shadow-sm">
                            <div class="card-body p-4">
                                <h5 class="card-title mb-3">
                                    <i class="fas fa-exchange-alt text-success me-2"></i> Help & Support
                                </h5>
                                <p class="card-text text-muted small">
                                    Have questions? We're here to help you anytime.
                                </p>
                                <a href="{{ route('contact') }}" class="btn btn-outline-success btn-sm">
                                    Contact Us
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .terms-content h2,
        .terms-content h3 {
            margin-top: 2rem;
            margin-bottom: 1rem;
            color: #212529;
        }

        .terms-content h2 {
            font-size: 1.5rem;
            font-weight: 600;
            border-bottom: 2px solid #007bff;
            padding-bottom: 0.5rem;
        }

        .terms-content h3 {
            font-size: 1.2rem;
            font-weight: 600;
        }

        .terms-content p {
            color: #6c757d;
            line-height: 1.8;
            margin-bottom: 1rem;
        }

        .terms-content ul,
        .terms-content ol {
            color: #6c757d;
            line-height: 1.8;
            margin-bottom: 1.5rem;
            padding-left: 1.5rem;
        }

        .terms-content li {
            margin-bottom: 0.5rem;
        }

        .terms-content strong {
            color: #212529;
        }
    </style>
@endpush

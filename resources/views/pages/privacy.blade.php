@extends('layouts.app')

@section('title', 'Privacy Policy - ElectroHub')
@section('description', 'Read ElectroHub privacy policy to understand how we collect and use your personal
    information.')

@section('content')
    <div class="container py-5">
        <!-- Page Header -->
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 mb-3">Privacy Policy</h1>
                <p class="lead text-muted">
                    @if ($privacyPolicy)
                        Last Updated: {{ $privacyPolicy->updated_at->format('F d, Y') }}
                    @else
                        Your privacy matters to us
                    @endif
                </p>
                <div class="border-bottom border-primary border-3 mx-auto" style="width: 100px;"></div>
            </div>
        </div>

        <!-- Privacy Policy Content -->
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card border shadow-sm">
                    <div class="card-body p-4 p-md-5">
                        @if ($privacyPolicy)
                            <h2 class="mb-4">{{ $privacyPolicy->title }}</h2>
                            <div class="privacy-content">
                                {!! $privacyPolicy->content !!}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-lock fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Privacy Policy is being updated. Please check back soon.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer CTA -->
        <div class="row mt-5">
            <div class="col-lg-8 mx-auto">
                <div class="card border-0 bg-light">
                    <div class="card-body p-4 text-center">
                        <p class="text-muted mb-2">
                            <i class="fas fa-question-circle me-2"></i> Have questions about our privacy practices?
                        </p>
                        <a href="{{ route('contact') }}" class="btn btn-primary">
                            <i class="fas fa-envelope me-2"></i> Contact Us
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .privacy-content h2,
        .privacy-content h3 {
            margin-top: 2rem;
            margin-bottom: 1rem;
            color: #212529;
        }

        .privacy-content h2 {
            font-size: 1.5rem;
            font-weight: 600;
            border-bottom: 2px solid #007bff;
            padding-bottom: 0.5rem;
        }

        .privacy-content h3 {
            font-size: 1.2rem;
            font-weight: 600;
        }

        .privacy-content p {
            color: #6c757d;
            line-height: 1.8;
            margin-bottom: 1rem;
        }

        .privacy-content ul,
        .privacy-content ol {
            color: #6c757d;
            line-height: 1.8;
            margin-bottom: 1.5rem;
            padding-left: 1.5rem;
        }

        .privacy-content li {
            margin-bottom: 0.5rem;
        }

        .privacy-content strong {
            color: #212529;
        }
    </style>
@endpush

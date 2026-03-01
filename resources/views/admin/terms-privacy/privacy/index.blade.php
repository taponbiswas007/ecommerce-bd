@extends('admin.layouts.master')

@section('title', 'Privacy Policy')
@section('page-title', 'Privacy Policy')
@section('page-subtitle', 'Manage Privacy Policy')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Privacy Policy</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            @if ($privacyPolicy)
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Edit Privacy Policy</h5>
                        <a href="{{ route('admin.privacy-policy.edit', $privacyPolicy->id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit me-1"></i> Edit
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6 class="text-muted">Title</h6>
                            <p class="mb-0">{{ $privacyPolicy->title }}</p>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <h6 class="text-muted">Content</h6>
                            <div class="border p-3 rounded bg-light">
                                {!! $privacyPolicy->content !!}
                            </div>
                        </div>
                        <hr>
                        <div>
                            <span class="badge {{ $privacyPolicy->is_active ? 'bg-success' : 'bg-danger' }}">
                                {{ $privacyPolicy->is_active ? 'Active' : 'Inactive' }}
                            </span>
                            <small class="text-muted ms-2">Last updated:
                                {{ $privacyPolicy->updated_at->format('M d, Y H:i') }}</small>
                        </div>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-exclamation-circle fa-3x text-warning mb-3"></i>
                        <h5>No Privacy Policy Found</h5>
                        <p class="text-muted mb-3">Create your first Privacy Policy</p>
                        <a href="{{ route('admin.privacy-policy.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i> Create Privacy Policy
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

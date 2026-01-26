@extends('admin.layouts.master')

@section('title', 'Create Ad')
@section('page-title', 'Create New Ad')
@section('page-subtitle', 'Add a new homepage ad')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.ads.index') }}">Ads</a></li>
    <li class="breadcrumb-item active">Create</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Add New Ad</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.ads.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="text" class="form-label">Text</label>
                    <input type="text" name="text" id="text" class="form-control" required
                        value="{{ old('text') }}">
                </div>
                <div class="mb-3">
                    <label for="badge" class="form-label">Badge</label>
                    <input type="text" name="badge" id="badge" class="form-control" required
                        value="{{ old('badge') }}">
                </div>
                <button type="submit" class="btn btn-success">Create</button>
                <a href="{{ route('admin.ads.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@endsection

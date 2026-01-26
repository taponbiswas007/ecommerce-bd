@extends('admin.layouts.master')

@section('title', 'Edit Ad')
@section('page-title', 'Edit Ad')
@section('page-subtitle', 'Update homepage ad details')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.ads.index') }}">Ads</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Edit Ad</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.ads.update', $ad) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="text" class="form-label">Text</label>
                    <input type="text" name="text" id="text" class="form-control" required
                        value="{{ old('text', $ad->text) }}">
                </div>
                <div class="mb-3">
                    <label for="badge" class="form-label">Badge</label>
                    <input type="text" name="badge" id="badge" class="form-control" required
                        value="{{ old('badge', $ad->badge) }}">
                </div>
                <button type="submit" class="btn btn-success">Update</button>
                <a href="{{ route('admin.ads.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@endsection

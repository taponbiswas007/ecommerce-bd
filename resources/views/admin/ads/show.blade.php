@extends('admin.layouts.master')

@section('title', 'Ad Details')
@section('page-title', 'Ad Details')
@section('page-subtitle', 'View homepage ad information')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.ads.index') }}">Ads</a></li>
    <li class="breadcrumb-item active">Details</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Ad Details</h4>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <strong>Text:</strong> {{ $ad->text }}
            </div>
            <div class="mb-3">
                <strong>Badge:</strong> {{ $ad->badge }}
            </div>
            <a href="{{ route('admin.ads.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
@endsection

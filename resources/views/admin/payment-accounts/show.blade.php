@extends('admin.layouts.master')

@section('title', 'Payment Account Details')
@section('page-title', 'Payment Account Details')
@section('page-subtitle', 'View account information')

@section('content')
    <div class="card border shadow-sm rounded-1">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ $paymentAccount->account_name }}</h5>
            <div>
                <a href="{{ route('admin.payment-accounts.edit', $paymentAccount) }}" class="btn btn-warning btn-sm">Edit</a>
                <a href="{{ route('admin.payment-accounts.index') }}" class="btn btn-outline-secondary btn-sm">Back</a>
            </div>
        </div>
        <div class="card-body">
            <p><strong>Method:</strong> {{ strtoupper(str_replace('_', ' ', $paymentAccount->method)) }}</p>
            <p><strong>Account Number:</strong> {{ $paymentAccount->account_number }}</p>
            <p><strong>Account Holder:</strong> {{ $paymentAccount->account_holder ?? 'N/A' }}</p>
            <p><strong>Branch:</strong> {{ $paymentAccount->branch ?? 'N/A' }}</p>
            <p><strong>Status:</strong>
                <span class="badge {{ $paymentAccount->is_active ? 'bg-success' : 'bg-secondary' }}">
                    {{ $paymentAccount->is_active ? 'Active' : 'Inactive' }}
                </span>
            </p>
            <p><strong>Instructions:</strong><br>{!! nl2br(e($paymentAccount->instructions ?? 'N/A')) !!}</p>
        </div>
    </div>
@endsection

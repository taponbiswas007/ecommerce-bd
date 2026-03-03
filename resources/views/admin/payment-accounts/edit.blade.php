@extends('admin.layouts.master')

@section('title', 'Edit Payment Account')
@section('page-title', 'Edit Payment Account')
@section('page-subtitle', 'Update payment account details')

@section('content')
    <div class="card border shadow-sm rounded-1">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.payment-accounts.update', $paymentAccount) }}">
                @csrf
                @method('PUT')
                @include('admin.payment-accounts._form')
            </form>
        </div>
    </div>
@endsection

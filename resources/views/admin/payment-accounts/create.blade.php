@extends('admin.layouts.master')

@section('title', 'Create Payment Account')
@section('page-title', 'Create Payment Account')
@section('page-subtitle', 'Add account details for customer payment')

@section('content')
    <div class="card border shadow-sm rounded-1">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.payment-accounts.store') }}">
                @csrf
                @include('admin.payment-accounts._form')
            </form>
        </div>
    </div>
@endsection

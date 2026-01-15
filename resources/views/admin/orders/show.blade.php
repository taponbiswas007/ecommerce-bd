@extends('admin.layouts.master')

@section('title', 'Order Details')
@section('page-title', 'Order Details')
@section('page-subtitle', 'View order information')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Orders</a></li>
    <li class="breadcrumb-item active">Order #{{ $order->id }}</li>
@endsection

@section('content')
    <div class="card border shadow-sm mb-4 rounded-1">
        <div class="card-body p-3">
            <x-admin.order-details :order="$order" />
        </div>
    </div>
@endsection

@extends('admin.layouts.master')

@section('title', 'Edit Order')
@section('page-title', 'Edit Order')
@section('page-subtitle', 'Update order information')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Orders</a></li>
    <li class="breadcrumb-item active">Edit Order #{{ $order->id }}</li>
@endsection

@section('content')
    <div class="card border shadow-sm mb-4 rounded-1">
        <div class="card-body p-3">
            <x-admin.order-edit-form :order="$order" />
        </div>
    </div>
@endsection

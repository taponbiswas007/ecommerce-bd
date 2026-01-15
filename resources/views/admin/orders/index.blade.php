@extends('admin.layouts.master')

@section('title', 'Orders')
@section('page-title', 'Orders')
@section('page-subtitle', 'Manage all orders')

@section('breadcrumb')
    <li class="breadcrumb-item active">Orders</li>
@endsection

@section('content')
    <div class="mb-3">
        <x-admin.sidebar-item route="admin.orders.index" :activeRoutes="['admin.orders.*']" icon="fas fa-shopping-cart" text="Orders"
            :badge="\App\Models\Order::where('order_status', 'pending')->count()" badgeClass="bg-danger" />
    </div>
    <div class="card border shadow-sm mb-4 rounded-1">
        <div class="card-body p-3">
            <h5 class="mb-3 fw-bold">Order List</h5>
            <x-admin.order-table :orders="$orders" />
        </div>
    </div>
@endsection

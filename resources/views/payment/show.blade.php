@extends('layouts.app')

@section('content')
    <div class="container py-6">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="card-body">
                <h4>Order #{{ $order->order_number }}</h4>
                <p><strong>Total:</strong> {{ number_format($order->total_amount, 2) }}</p>
                <p><strong>Payment Method:</strong> {{ strtoupper($order->payment_method) }}</p>
                <p><strong>Payment Status:</strong> {{ ucfirst($order->payment_status) }}</p>

                @if ($order->payment_method === 'bank_transfer')
                    <div class="alert alert-info">
                        Please transfer the total amount to our bank account and upload the proof on the orders page.
                    </div>
                @endif

                <a href="{{ route('orders.index') }}" class="btn btn-secondary">Go to My Orders</a>
            </div>
        </div>
    </div>
@endsection

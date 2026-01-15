@props(['status'])
@php
    $map = [
        'pending' => ['bg-warning', 'Pending'],
        'approved' => ['bg-success', 'Approved'],
        'cancelled' => ['bg-danger', 'Cancelled'],
        'shipped' => ['bg-info', 'Shipped'],
        'delivered' => ['bg-primary', 'Delivered'],
    ];
    [$class, $label] = $map[$status] ?? ['bg-secondary', ucfirst($status)];
@endphp
<span class="badge {{ $class }}">{{ $label }}</span>

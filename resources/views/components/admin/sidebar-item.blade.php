@props([
    'route', // route name, e.g. 'admin.orders.index'
    'icon', // FontAwesome icon class, e.g. 'fas fa-shopping-cart'
    'text', // Menu text, e.g. 'Orders'
    'activeRoutes' => [], // array of route patterns for active state
    'badge' => null, // badge value (optional)
    'badgeClass' => 'bg-danger', // badge color class
])

<li class="{{ request()->routeIs(...$activeRoutes) ? 'active' : '' }}">
    <a href="{{ route($route) }}">
        <i class="{{ $icon }}"></i>
        <span class="menu-text">{{ $text }}</span>
        @if ($badge)
            <span class="badge {{ $badgeClass }} ms-auto">{{ $badge }}</span>
        @endif
    </a>
</li>

<nav class="bg-gray-800">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="ml-2 text-xl font-bold text-white">Admin Panel</span>
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <a href="{{ route('admin.dashboard') }}"
                            class="{{ request()->routeIs('admin.dashboard*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} rounded-md px-3 py-2 text-sm font-medium">
                            <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
                        </a>

                        <a href="{{ route('admin.products.index') }}"
                            class="{{ request()->routeIs('admin.products*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} rounded-md px-3 py-2 text-sm font-medium">
                            <i class="fas fa-box mr-1"></i> Products
                        </a>

                        <a href="{{ route('admin.categories.index') }}"
                            class="{{ request()->routeIs('admin.categories*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} rounded-md px-3 py-2 text-sm font-medium">
                            <i class="fas fa-tags mr-1"></i> Categories
                        </a>

                        <a href="{{ route('admin.orders.index') }}"
                            class="{{ request()->routeIs('admin.orders*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} rounded-md px-3 py-2 text-sm font-medium">
                            <i class="fas fa-shopping-cart mr-1"></i> Orders
                        </a>

                        <a href="{{ route('admin.customers.index') }}"
                            class="{{ request()->routeIs('admin.customers*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} rounded-md px-3 py-2 text-sm font-medium">
                            <i class="fas fa-users mr-1"></i> Customers
                        </a>

                        <a href="{{ route('admin.coupons.index') }}"
                            class="{{ request()->routeIs('admin.coupons*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} rounded-md px-3 py-2 text-sm font-medium">
                            <i class="fas fa-ticket-alt mr-1"></i> Coupons
                        </a>

                        <!-- More dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open"
                                class="{{ request()->routeIs('admin.reviews*') || request()->routeIs('admin.settings*') || request()->routeIs('admin.reports*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} flex items-center rounded-md px-3 py-2 text-sm font-medium">
                                <i class="fas fa-ellipsis-h mr-1"></i> More
                                <svg class="ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>

                            <div x-show="open" @click.away="open = false"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute left-0 z-10 mt-2 w-48 origin-top-left rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5">
                                <a href="{{ route('admin.reviews.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-star mr-2"></i> Reviews
                                </a>
                                <a href="{{ route('admin.settings.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-cog mr-2"></i> Settings
                                </a>
                                <a href="{{ route('admin.reports.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-chart-bar mr-2"></i> Reports
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side -->
            <div class="hidden md:block">
                <div class="ml-4 flex items-center md:ml-6">
                    <!-- Notifications -->
                    <div class="relative ml-3" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="relative rounded-full bg-gray-800 p-1 text-gray-400 hover:text-white focus:outline-none">
                            <span class="sr-only">View notifications</span>
                            <i class="fas fa-bell h-6 w-6"></i>
                            @php
                                $pendingOrders = \App\Models\Order::where('order_status', 'pending')->count();
                                $pendingReviews = \App\Models\Review::where('status', 'pending')->count();
                                $totalNotifications = $pendingOrders + $pendingReviews;
                            @endphp
                            @if ($totalNotifications > 0)
                                <span
                                    class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-xs text-white">
                                    {{ $totalNotifications }}
                                </span>
                            @endif
                        </button>

                        <div x-show="open" @click.away="open = false"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 z-10 mt-2 w-80 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5">
                            <div class="px-4 py-2 border-b border-gray-100">
                                <h3 class="text-sm font-medium text-gray-900">Notifications</h3>
                            </div>

                            <div class="max-h-64 overflow-y-auto">
                                @if ($pendingOrders > 0)
                                    <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}"
                                        class="flex items-center px-4 py-3 hover:bg-gray-50 border-b border-gray-100">
                                        <div class="flex-shrink-0">
                                            <div
                                                class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center">
                                                <i class="fas fa-shopping-cart text-yellow-600 text-sm"></i>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">{{ $pendingOrders }} Pending
                                                Orders</p>
                                            <p class="text-xs text-gray-500">Need your attention</p>
                                        </div>
                                    </a>
                                @endif

                                @if ($pendingReviews > 0)
                                    <a href="{{ route('admin.reviews.index') }}"
                                        class="flex items-center px-4 py-3 hover:bg-gray-50 border-b border-gray-100">
                                        <div class="flex-shrink-0">
                                            <div
                                                class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                                <i class="fas fa-star text-blue-600 text-sm"></i>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">{{ $pendingReviews }} Pending
                                                Reviews</p>
                                            <p class="text-xs text-gray-500">Awaiting approval</p>
                                        </div>
                                    </a>
                                @endif

                                <!-- Low Stock Products -->
                                @php
                                    $lowStockProducts = \App\Models\Product::where('stock_quantity', '<=', 10)
                                        ->where('is_active', true)
                                        ->count();
                                @endphp
                                @if ($lowStockProducts > 0)
                                    <a href="{{ route('admin.products.index', ['low_stock' => true]) }}"
                                        class="flex items-center px-4 py-3 hover:bg-gray-50">
                                        <div class="flex-shrink-0">
                                            <div
                                                class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center">
                                                <i class="fas fa-exclamation-triangle text-red-600 text-sm"></i>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">{{ $lowStockProducts }} Low
                                                Stock Products</p>
                                            <p class="text-xs text-gray-500">Need restocking</p>
                                        </div>
                                    </a>
                                @endif

                                @if ($totalNotifications == 0)
                                    <div class="px-4 py-6 text-center">
                                        <i class="fas fa-check-circle text-green-500 text-2xl mb-2"></i>
                                        <p class="text-sm text-gray-500">No new notifications</p>
                                    </div>
                                @endif
                            </div>

                            @if ($totalNotifications > 0)
                                <div class="border-t border-gray-100">
                                    <a href="#"
                                        class="block px-4 py-2 text-sm text-center text-primary-600 hover:bg-gray-50">
                                        View all notifications
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- View Website -->
                    <a href="{{ route('home') }}" target="_blank"
                        class="ml-4 rounded-md bg-gray-700 px-3 py-2 text-sm font-medium text-white hover:bg-gray-600">
                        <i class="fas fa-external-link-alt mr-1"></i> View Website
                    </a>

                    <!-- User Menu -->
                    <div class="relative ml-3" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="flex max-w-xs items-center rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
                            <span class="sr-only">Open user menu</span>
                            <div
                                class="h-8 w-8 rounded-full bg-primary-500 flex items-center justify-center text-white font-semibold">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        </button>

                        <div x-show="open" @click.away="open = false"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5">
                            <div class="px-4 py-2 border-b border-gray-100">
                                <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                            </div>

                            <a href="{{ route('dashboard') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-user mr-2"></i> My Profile
                            </a>

                            <a href="{{ route('profile.edit') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-cog mr-2"></i> Settings
                            </a>

                            <div class="border-t border-gray-100"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile menu button -->
            <div class="-mr-2 flex md:hidden">
                <button @click="open = !open"
                    class="inline-flex items-center justify-center rounded-md bg-gray-800 p-2 text-gray-400 hover:bg-gray-700 hover:text-white">
                    <span class="sr-only">Open main menu</span>
                    <svg class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div class="md:hidden" x-show="open" @click.away="open = false">
        <div class="space-y-1 px-2 pb-3 pt-2 sm:px-3">
            <a href="{{ route('admin.dashboard') }}"
                class="{{ request()->routeIs('admin.dashboard*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} block rounded-md px-3 py-2 text-base font-medium">
                <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
            </a>

            <a href="{{ route('admin.products.index') }}"
                class="{{ request()->routeIs('admin.products*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} block rounded-md px-3 py-2 text-base font-medium">
                <i class="fas fa-box mr-2"></i> Products
            </a>

            <a href="{{ route('admin.categories.index') }}"
                class="{{ request()->routeIs('admin.categories*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} block rounded-md px-3 py-2 text-base font-medium">
                <i class="fas fa-tags mr-2"></i> Categories
            </a>

            <a href="{{ route('admin.orders.index') }}"
                class="{{ request()->routeIs('admin.orders*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} block rounded-md px-3 py-2 text-base font-medium">
                <i class="fas fa-shopping-cart mr-2"></i> Orders
            </a>

            <a href="{{ route('admin.customers.index') }}"
                class="{{ request()->routeIs('admin.customers*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} block rounded-md px-3 py-2 text-base font-medium">
                <i class="fas fa-users mr-2"></i> Customers
            </a>

            <a href="{{ route('admin.coupons.index') }}"
                class="{{ request()->routeIs('admin.coupons*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} block rounded-md px-3 py-2 text-base font-medium">
                <i class="fas fa-ticket-alt mr-2"></i> Coupons
            </a>

            <a href="{{ route('admin.reviews.index') }}"
                class="{{ request()->routeIs('admin.reviews*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} block rounded-md px-3 py-2 text-base font-medium">
                <i class="fas fa-star mr-2"></i> Reviews
            </a>
        </div>

        <div class="border-t border-gray-700 pb-3 pt-4">
            <div class="flex items-center px-5">
                <div class="flex-shrink-0">
                    <div
                        class="h-10 w-10 rounded-full bg-primary-500 flex items-center justify-center text-white font-semibold">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                </div>
                <div class="ml-3">
                    <div class="text-base font-medium text-white">{{ auth()->user()->name }}</div>
                    <div class="text-sm font-medium text-gray-400">{{ auth()->user()->email }}</div>
                </div>

                <!-- Mobile Notifications -->
                <div class="ml-auto">
                    <button class="relative rounded-full bg-gray-800 p-1 text-gray-400 hover:text-white">
                        <i class="fas fa-bell h-6 w-6"></i>
                        @if ($totalNotifications > 0)
                            <span
                                class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-xs text-white">
                                {{ $totalNotifications }}
                            </span>
                        @endif
                    </button>
                </div>
            </div>

            <div class="mt-3 space-y-1 px-2">
                <a href="{{ route('dashboard') }}"
                    class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-gray-700 hover:text-white">
                    <i class="fas fa-user mr-2"></i> My Profile
                </a>

                <a href="{{ route('profile.edit') }}"
                    class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-gray-700 hover:text-white">
                    <i class="fas fa-cog mr-2"></i> Settings
                </a>

                <a href="{{ route('home') }}" target="_blank"
                    class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-gray-700 hover:text-white">
                    <i class="fas fa-external-link-alt mr-2"></i> View Website
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="block w-full rounded-md px-3 py-2 text-left text-base font-medium text-gray-400 hover:bg-gray-700 hover:text-white">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

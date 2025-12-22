{{-- <nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav> --}}
<nav class="bg-white shadow-lg">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 justify-between">
            <div class="flex">
                <!-- Logo -->
                <div class="flex flex-shrink-0 items-center">
                    <a href="{{ route('home') }}">
                        <span class="text-2xl font-bold text-primary-600">EcommerceBD</span>
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                    <a href="{{ route('home') }}"
                        class="{{ request()->routeIs('home') ? 'border-primary-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center border-b-2 px-1 pt-1 text-sm font-medium">
                        Home
                    </a>
                    <a href="{{ route('shop') }}"
                        class="{{ request()->routeIs('shop') ? 'border-primary-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center border-b-2 px-1 pt-1 text-sm font-medium">
                        Shop
                    </a>

                    <!-- Categories Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="{{ request()->routeIs('category.*') ? 'border-primary-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center border-b-2 px-1 pt-1 text-sm font-medium">
                            Categories
                            <svg class="ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false"
                            class="absolute left-0 z-10 mt-2 w-48 origin-top-left rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5">
                            @php
                                $categories = \App\Models\Category::whereNull('parent_id')
                                    ->where('is_active', true)
                                    ->with('children')
                                    ->get();
                            @endphp

                            @foreach ($categories as $category)
                                <a href="{{ route('category.show', $category->slug) }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    {{ $category->name }}
                                </a>
                                @foreach ($category->children as $child)
                                    <a href="{{ route('category.show', $child->slug) }}"
                                        class="block px-8 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        - {{ $child->name }}
                                    </a>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side Navigation -->
            <div class="flex items-center">
                <!-- Search -->
                <div class="hidden sm:block">
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="search"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2 pl-10 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500"
                            placeholder="Search products...">
                    </div>
                </div>

                <!-- Icons -->
                <div class="ml-4 flex items-center space-x-4">
                    <!-- Wishlist -->
                    <a href="{{ route('wishlist.index') }}" class="relative text-gray-600 hover:text-primary-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        @auth
                            @php
                                $wishlistCount = auth()->user()->wishlist()->count();
                            @endphp
                            @if ($wishlistCount > 0)
                                <span
                                    class="absolute -top-2 -right-2 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-xs text-white">
                                    {{ $wishlistCount }}
                                </span>
                            @endif
                        @endauth
                    </a>

                    <!-- Cart -->
                    <a href="{{ route('cart.index') }}" class="relative text-gray-600 hover:text-primary-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        @php
                            $cartCount = \App\Models\Cart::count();
                        @endphp
                        @if ($cartCount > 0)
                            <span
                                class="absolute -top-2 -right-2 flex h-5 w-5 items-center justify-center rounded-full bg-primary-500 text-xs text-white">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>

                    <!-- User Menu -->
                    @auth
                        <div class="relative ml-4" x-data="{ open: false }">
                            <button @click="open = !open"
                                class="flex rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
                                <span class="sr-only">Open user menu</span>
                                <div
                                    class="h-8 w-8 rounded-full bg-primary-500 flex items-center justify-center text-white font-semibold">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            </button>

                            <div x-show="open" @click.away="open = false"
                                class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5">
                                @if (auth()->user()->role === 'admin')
                                    <a href="{{ route('admin.dashboard') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Admin Dashboard
                                    </a>
                                    <div class="border-t border-gray-100"></div>
                                @endif

                                <a href="{{ route('dashboard') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    My Account
                                </a>
                                <a href="{{ route('profile.edit') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Profile
                                </a>
                                <div class="border-t border-gray-100"></div>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="hidden sm:ml-6 sm:flex sm:items-center sm:space-x-4">
                            <a href="{{ route('login') }}"
                                class="rounded-md px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-900">
                                Login
                            </a>
                            <a href="{{ route('register') }}"
                                class="rounded-md bg-primary-600 px-3 py-2 text-sm font-medium text-white hover:bg-primary-700">
                                Register
                            </a>
                        </div>
                    @endauth
                </div>

                <!-- Mobile menu button -->
                <div class="-mr-2 flex items-center sm:hidden">
                    <button @click="open = !open"
                        class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-500">
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
    </div>

    <!-- Mobile menu -->
    <div class="sm:hidden" x-show="open" @click.away="open = false">
        <div class="space-y-1 pb-3 pt-2">
            <a href="{{ route('home') }}"
                class="{{ request()->routeIs('home') ? 'border-primary-500 bg-primary-50 text-primary-700' : 'border-transparent text-gray-500 hover:border-gray-300 hover:bg-gray-50 hover:text-gray-700' }} block border-l-4 py-2 pl-3 pr-4 text-base font-medium">
                Home
            </a>
            <a href="{{ route('shop') }}"
                class="{{ request()->routeIs('shop') ? 'border-primary-500 bg-primary-50 text-primary-700' : 'border-transparent text-gray-500 hover:border-gray-300 hover:bg-gray-50 hover:text-gray-700' }} block border-l-4 py-2 pl-3 pr-4 text-base font-medium">
                Shop
            </a>

            <!-- Mobile Categories -->
            <div class="border-l-4 border-transparent">
                <div class="py-2 pl-3 pr-4 text-base font-medium text-gray-500">
                    Categories
                </div>
                <div class="space-y-1 pl-6">
                    @php
                        $categories = \App\Models\Category::whereNull('parent_id')
                            ->where('is_active', true)
                            ->with('children')
                            ->get();
                    @endphp

                    @foreach ($categories as $category)
                        <a href="{{ route('category.show', $category->slug) }}"
                            class="block py-2 pl-3 pr-4 text-base font-medium text-gray-500 hover:border-gray-300 hover:bg-gray-50 hover:text-gray-700">
                            {{ $category->name }}
                        </a>
                        @foreach ($category->children as $child)
                            <a href="{{ route('category.show', $child->slug) }}"
                                class="block py-2 pl-6 pr-4 text-base font-medium text-gray-500 hover:border-gray-300 hover:bg-gray-50 hover:text-gray-700">
                                - {{ $child->name }}
                            </a>
                        @endforeach
                    @endforeach
                </div>
            </div>

            @guest
                <div class="border-t border-gray-200 pt-4">
                    <a href="{{ route('login') }}"
                        class="block border-l-4 border-transparent py-2 pl-3 pr-4 text-base font-medium text-gray-500 hover:border-gray-300 hover:bg-gray-50 hover:text-gray-700">
                        Login
                    </a>
                    <a href="{{ route('register') }}"
                        class="block border-l-4 border-transparent py-2 pl-3 pr-4 text-base font-medium text-gray-500 hover:border-gray-300 hover:bg-gray-50 hover:text-gray-700">
                        Register
                    </a>
                </div>
            @endguest
        </div>
    </div>
</nav>

<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Dashboard') | {{ config('app.name') }} Admin</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Alpine.js for dropdowns -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- jQuery (if needed) -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<body class="h-full">
    <!-- Admin Layout -->
    <div class="min-h-full">
        <!-- Admin Navigation -->
        @include('admin.layouts.navigation')

        <!-- Admin Content -->
        <div class="py-10">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- Page Header -->
                <div class="mb-8 px-4 sm:px-0">
                    <div class="md:flex md:items-center md:justify-between">
                        <div class="min-w-0 flex-1">
                            <h2
                                class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                                @yield('title', 'Dashboard')
                            </h2>
                            @hasSection('subtitle')
                                <p class="mt-1 text-sm text-gray-500">@yield('subtitle')</p>
                            @endif
                        </div>
                        <div class="mt-4 flex md:ml-4 md:mt-0">
                            @yield('actions')
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="px-4 sm:px-0">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    @stack('scripts')

    <script>
        // Initialize SweetAlert
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        // Flash Messages
        @if (session('success'))
            Toast.fire({
                icon: 'success',
                title: '{{ session('success') }}'
            });
        @endif

        @if (session('error'))
            Toast.fire({
                icon: 'error',
                title: '{{ session('error') }}'
            });
        @endif

        @if (session('warning'))
            Toast.fire({
                icon: 'warning',
                title: '{{ session('warning') }}'
            });
        @endif

        @if (session('info'))
            Toast.fire({
                icon: 'info',
                title: '{{ session('info') }}'
            });
        @endif
    </script>
    <script src="https://cdn.tailwindcss.com"></script>

</body>

</html>

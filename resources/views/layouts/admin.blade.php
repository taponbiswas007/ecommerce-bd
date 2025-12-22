<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin @yield('title', 'Dashboard') | {{ config('app.name') }}</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Additional Styles -->
    @stack('styles')

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- TinyMCE -->
    <script src="https://cdn.tiny.cloud/1/YOUR_API_KEY/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
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
                <div class="mb-8">
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
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="px-4 py-6 sm:p-6">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    @stack('scripts')

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <!-- Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Initialize Admin Scripts -->
    <script>
        $(document).ready(function() {
            // Initialize DataTables
            $('.data-table').DataTable({
                responsive: true,
                pageLength: 25,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search..."
                }
            });

            // Initialize Select2
            $('.select2').select2({
                theme: 'bootstrap-5'
            });

            // Initialize TinyMCE
            if (typeof tinymce !== 'undefined') {
                tinymce.init({
                    selector: '.tinymce',
                    height: 400,
                    menubar: false,
                    plugins: [
                        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                        'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                        'insertdatetime', 'media', 'table', 'code', 'help', 'wordcount'
                    ],
                    toolbar: 'undo redo | blocks | ' +
                        'bold italic forecolor | alignleft aligncenter ' +
                        'alignright alignjustify | bullist numlist outdent indent | ' +
                        'removeformat | help',
                    content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }'
                });
            }

            // Confirm delete
            $('body').on('click', '.confirm-delete', function(e) {
                e.preventDefault();
                const form = $(this).closest('form');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>

    <!-- Flash Messages -->
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session('error') }}',
                timer: 3000,
                showConfirmButton: false
            });
        </script>
    @endif
</body>

</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'His Kingdom Church') }}</title>

    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1a365d',    // You can adjust this to match your church's primary color
                        secondary: '#ed8936',  // You can adjust this to match your church's secondary color
                    },
                },
            },
        }
    </script>
    @viteReactRefresh
    @vite(['resources/sass/app.scss', 'resources/js/app.jsx'])
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .transition-all {
            transition: all 0.3s ease-in-out;
        }
        /* Add space for fixed navigation */
        main {
            padding-top: 4rem; /* Adjust this value based on your navbar height */
        }
    </style>
</head>
<body class="min-h-screen flex flex-col bg-gray-100">
    <!-- Include Navigation -->
    @include('layouts.navigation')

    <!-- Page Content -->
    <main class="flex-grow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @yield('content')
        </div>
    </main>

    <!-- Include Footer -->
    @include('layouts.footer')

    @stack('scripts')
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'His Kingdom Church'))</title>

    <!-- Meta tags for social sharing -->
    @yield('meta')

    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#011EB7',      // Updated to match corporate blue
                        secondary: '#E0B041',    // Updated to match corporate gold
                        accent: '#754DA4'        // Updated to match corporate purple
                    },
                    boxShadow: {
                        'custom': '0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
                    }
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
        .text-shadow {
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
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
<body class="min-h-screen flex flex-col bg-gray-50">
    <!-- Include Navigation -->
    @include('layouts.navigation')

    <!-- Page Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Include Footer -->
    @include('layouts.footer')

    @stack('scripts')
</body>
</html>

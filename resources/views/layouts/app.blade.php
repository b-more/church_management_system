<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gallery</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @viteReactRefresh
    @vite(['resources/sass/app.scss', 'resources/js/app.jsx'])
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .transition-all {
            transition: all 0.3s ease-in-out;
        }
    </style>
</head>
<body>
    <main>
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>

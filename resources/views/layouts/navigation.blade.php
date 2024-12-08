<!-- resources/views/layouts/navigation.blade.php -->
<nav class="bg-white shadow-sm fixed w-full z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="/" class="flex items-center">
                    <img src="{{ asset('images/black_logo.png') }}" alt="HKC Logo" class="h-40 w-auto py-2">
                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="/" class="text-gray-600 hover:text-primary">Home</a>
                <a href="/about" class="text-gray-600 hover:text-primary">About</a>
                <a href="/services" class="text-gray-600 hover:text-primary">Services</a>
                <a href="/gallery" class="text-gray-600 hover:text-primary">Gallery</a>
                <a href="/contact" class="text-gray-600 hover:text-primary">Contact</a>
                <a href="/admin" class="bg-primary text-white px-4 py-2 rounded-md hover:bg-primary/90 transition-colors">Admin Portal</a>
            </div>

            <!-- Mobile Menu Button -->
            <div class="md:hidden flex items-center">
                <button id="mobile-menu-button" class="text-gray-600 hover:text-primary">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="/" class="block px-3 py-2 rounded-md text-gray-600 hover:bg-primary hover:text-white">Home</a>
                <a href="/about" class="block px-3 py-2 rounded-md text-gray-600 hover:bg-primary hover:text-white">About</a>
                <a href="/services" class="block px-3 py-2 rounded-md text-gray-600 hover:bg-primary hover:text-white">Services</a>
                <a href="/gallery" class="block px-3 py-2 rounded-md text-gray-600 hover:bg-primary hover:text-white">Gallery</a>
                <a href="/contact" class="block px-3 py-2 rounded-md text-gray-600 hover:bg-primary hover:text-white">Contact</a>
                <a href="/admin" class="block px-3 py-2 rounded-md bg-primary text-white hover:bg-primary/90">Admin Portal</a>
            </div>
        </div>
    </div>
</nav>

<script>
    // Mobile menu toggle
    document.getElementById('mobile-menu-button').addEventListener('click', function() {
        document.getElementById('mobile-menu').classList.toggle('hidden');
    });
</script>
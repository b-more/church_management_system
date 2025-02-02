<!-- resources/views/layouts/navigation.blade.php -->
<nav class="bg-white fixed w-full z-50 transition-shadow duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo Section - Enhanced with better spacing and hover effect -->
            <div class="flex items-center">
                <a href="/" class="flex items-center transition-opacity duration-300 hover:opacity-90">
                    <img src="{{ asset('images/black_logo.png') }}" alt="HKC Logo" class="h-40 w-auto py-2">
                </a>
            </div>

            <!-- Desktop Navigation - Enhanced with better transitions and hover effects -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="/" class="text-gray-600 hover:text-primary transition-colors duration-300 font-medium relative group">
                    Home
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="/about" class="text-gray-600 hover:text-primary transition-colors duration-300 font-medium relative group">
                    About
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="/services" class="text-gray-600 hover:text-primary transition-colors duration-300 font-medium relative group">
                    Services
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="/gallery" class="text-gray-600 hover:text-primary transition-colors duration-300 font-medium relative group">
                    Gallery
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="/contact" class="text-gray-600 hover:text-primary transition-colors duration-300 font-medium relative group">
                    Contact
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="/admin"
                   class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-primary/90 transition-all duration-300
                          transform hover:-translate-y-0.5 hover:shadow-lg font-medium">
                    Admin Portal
                </a>
            </div>

            <!-- Mobile Menu Button - Enhanced with smoother animation -->
            <div class="md:hidden flex items-center">
                <button id="mobile-menu-button"
                        class="text-gray-600 hover:text-primary transition-colors duration-300 p-2 focus:outline-none"
                        aria-label="Toggle mobile menu">
                    <svg id="menu-icon" class="h-6 w-6 transform transition-transform duration-300"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu - Enhanced with smooth transitions and better spacing -->
        <div id="mobile-menu"
             class="hidden md:hidden transform transition-transform duration-300 ease-in-out origin-top">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="/"
                   class="block px-4 py-3 rounded-lg text-gray-600 hover:bg-primary/10 hover:text-primary
                          transition-all duration-300 font-medium">
                    Home
                </a>
                <a href="/about"
                   class="block px-4 py-3 rounded-lg text-gray-600 hover:bg-primary/10 hover:text-primary
                          transition-all duration-300 font-medium">
                    About
                </a>
                <a href="/services"
                   class="block px-4 py-3 rounded-lg text-gray-600 hover:bg-primary/10 hover:text-primary
                          transition-all duration-300 font-medium">
                    Services
                </a>
                <a href="/gallery"
                   class="block px-4 py-3 rounded-lg text-gray-600 hover:bg-primary/10 hover:text-primary
                          transition-all duration-300 font-medium">
                    Gallery
                </a>
                <a href="/contact"
                   class="block px-4 py-3 rounded-lg text-gray-600 hover:bg-primary/10 hover:text-primary
                          transition-all duration-300 font-medium">
                    Contact
                </a>
                <a href="/admin"
                   class="block px-4 py-3 mt-2 rounded-lg bg-primary text-white hover:bg-primary/90
                          transition-all duration-300 font-medium text-center">
                    Admin Portal
                </a>
            </div>
        </div>
    </div>
</nav>

<script>
    // Enhanced mobile menu toggle with animation
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    const menuIcon = document.getElementById('menu-icon');

    let isMenuOpen = false;

    mobileMenuButton.addEventListener('click', function() {
        isMenuOpen = !isMenuOpen;

        // Toggle menu visibility with animation
        if (isMenuOpen) {
            mobileMenu.classList.remove('hidden');
            mobileMenu.classList.add('scale-y-100');
            mobileMenu.classList.remove('scale-y-0');
            menuIcon.classList.add('rotate-90');
        } else {
            mobileMenu.classList.remove('scale-y-100');
            mobileMenu.classList.add('scale-y-0');
            menuIcon.classList.remove('rotate-90');
            setTimeout(() => {
                if (!isMenuOpen) { // Check again to prevent race conditions
                    mobileMenu.classList.add('hidden');
                }
            }, 300);
        }
    });

    // Add shadow on scroll
    window.addEventListener('scroll', function() {
        const nav = document.querySelector('nav');
        if (window.scrollY > 0) {
            nav.classList.add('shadow-md');
        } else {
            nav.classList.remove('shadow-md');
        }
    });
</script>

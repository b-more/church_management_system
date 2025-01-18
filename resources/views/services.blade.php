<!-- resources/views/services.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services - His Kingdom Church</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#011EB7',
                        secondary: '#E0B041',
                        accent: '#754DA4'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50">
     <!-- Navigation -->
     <nav class="bg-white shadow-sm fixed w-full z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <div class="h-8 w-8 bg-primary rounded-full flex items-center justify-center">
                        <span class="text-white font-bold">HKC</span>
                    </div>
                    <span class="ml-2 text-lg font-bold text-primary">His Kingdom Church</span>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#home" class="text-primary font-medium hover:text-secondary transition-colors">Home</a>
                    <a href="{{ route('about') }}" class="text-gray-600 hover:text-primary">About Us</a>
                    <a href="#services" class="text-gray-600 font-medium hover:text-secondary transition-colors">Services</a>
                    <a href="#contact" class="text-gray-600 font-medium hover:text-secondary transition-colors">Contact</a>
                    <a href="/admin" class="bg-primary text-white px-4 py-2 rounded-md hover:bg-primary/90 transition-colors">Admin Portal</a>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center">
                    <button id="menuButton" class="text-gray-600 hover:text-primary">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-16 6h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden md:hidden bg-white border-t px-2 pt-2 pb-3 space-y-1">
            <a href="#home" class="block px-3 py-2 text-primary font-medium hover:bg-gray-50 rounded-md">Home</a>
            <a href="#about" class="block px-3 py-2 text-gray-600 font-medium hover:bg-gray-50 rounded-md">About</a>
            <a href="#services" class="block px-3 py-2 text-gray-600 font-medium hover:bg-gray-50 rounded-md">Services</a>
            <a href="#contact" class="block px-3 py-2 text-gray-600 font-medium hover:bg-gray-50 rounded-md">Contact</a>
            <a href="/admin" class="block px-3 py-2 text-gray-600 font-medium hover:bg-gray-50 rounded-md">Admin Portal</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative pt-16 pb-32 flex content-center items-center justify-center min-h-[400px]">
        <div class="absolute top-0 w-full h-full bg-center bg-cover" style="background-image: url('{{ asset('images/hero.jpg') }}');">
            <span class="w-full h-full absolute opacity-75 bg-primary"></span>
        </div>
        <div class="container relative mx-auto px-4">
            <div class="items-center flex flex-wrap">
                <div class="w-full lg:w-6/12 mx-auto text-center">
                    <div class="text-white">
                        <h1 class="text-5xl font-bold">Our Services</h1>
                        <p class="mt-4 text-lg">Join us for worship and fellowship</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Weekly Services Section -->
    <div class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-primary text-center mb-12">Weekly Services</h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Sunday Service -->
                <div class="bg-gray-50 rounded-lg p-8">
                    <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Sunday Service</h3>
                    <p class="text-secondary font-medium mb-4">08:30 AM - 12:30 PM</p>
                    <p class="text-gray-600">Experience powerful worship, relevant teaching, and warm fellowship every Sunday morning.</p>
                </div>

                <!-- Bible Study -->
                <div class="bg-gray-50 rounded-lg p-8">
                    <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Midweek Services</h3>
                    <p class="text-secondary font-medium mb-4">Wednesday 5:30 PM - 7:00 PM</p>
                    <p class="text-gray-600">Deepen your understanding of God's Word through in-depth Midweek service.</p>
                </div>

                <!-- Prayer Service -->
                <div class="bg-gray-50 rounded-lg p-8">
                    <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Online Prayer Service</h3>
                    <p class="text-secondary font-medium mb-4">Friday 9:00 PM</p>
                    <p class="text-gray-600">Join us for powerful intercessory prayer and spiritual warfare online.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Ministries Section -->
    <div class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-primary text-center mb-12">Our Ministries</h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Children's Ministry -->
                <div class="bg-white rounded-lg p-6 shadow-sm">
                    <div class="w-12 h-12 bg-accent/10 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">King's Kids Ministry</h3>
                    <p class="text-gray-600">Nurturing young hearts in faith through age-appropriate teaching and activities.</p>
                </div>

                <!-- Youth Ministry -->
                <div class="bg-white rounded-lg p-6 shadow-sm">
                    <div class="w-12 h-12 bg-accent/10 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Transformed Youth Ministry</h3>
                    <p class="text-gray-600">Empowering the next generation through relevant teaching and mentorship.</p>
                </div>

                <!-- Women's Ministry -->
                <div class="bg-white rounded-lg p-6 shadow-sm">
                    <div class="w-12 h-12 bg-accent/10 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Royal Women's Ministry</h3>
                    <p class="text-gray-600">Building strong women of faith through fellowship and discipleship.</p>
                </div>

                <!-- Men's Ministry -->
                <div class="bg-white rounded-lg p-6 shadow-sm">
                    <div class="w-12 h-12 bg-accent/10 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Men's Ministry</h3>
                    <p class="text-gray-600">Developing Godly men through fellowship and spiritual growth.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Section -->
    <div class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-primary text-center mb-12">Join Us This Week</h2>
            <div class="max-w-2xl mx-auto text-center">
                <p class="text-gray-600 mb-8">We'd love to have you join us for any of our services. If you have any questions, please don't hesitate to contact us.</p>
                <a href="{{ route('contact') }}" class="inline-block bg-primary text-white px-8 py-3 rounded-full hover:bg-primary/90 transition-colors">
                    Contact Us
                </a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-primary text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Quick Links -->
                <div>
                    <h3 class="text-secondary font-semibold text-lg mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="#home" class="text-white/80 hover:text-secondary transition-colors">Home</a></li>
                        <li><a href="#about" class="text-white/80 hover:text-secondary transition-colors">About</a></li>
                        <li><a href="#services" class="text-white/80 hover:text-secondary transition-colors">Services</a></li>
                        <li><a href="#contact" class="text-white/80 hover:text-secondary transition-colors">Contact</a></li>
                        <li><a href="/admin" class="text-white/80 hover:text-secondary transition-colors">Admin Portal</a></li>
                    </ul>
                </div>

                <!-- Service Times -->
                <div>
                    <h3 class="text-secondary font-semibold text-lg mb-4">Service Times</h3>
                    <ul class="space-y-2">
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-secondary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Sunday: 10:00 AM</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-secondary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Wednesday: 6:00 PM</span>
                        </li>
                    </ul>
                </div>

                <!-- Newsletter Signup -->
                <div class="md:col-span-2">
                    <h3 class="text-secondary font-semibold text-lg mb-4">Stay Connected</h3>
                    <p class="text-white/80 mb-4">Subscribe to our newsletter for updates</p>
                    <form class="flex" action="{{ route('newsletter.subscribe') }}" method="POST">
                        @csrf
                        <input type="email" name="email" placeholder="Your email" required
                               class="flex-1 px-4 py-2 rounded-l-lg focus:outline-none text-gray-800">
                        <button type="submit"
                                class="bg-secondary px-4 py-2 rounded-r-lg hover:bg-secondary/90 transition-colors">
                            Subscribe
                        </button>
                    </form>
                </div>
            </div>

            <!-- Bottom Footer -->
            <div class="mt-12 pt-8 border-t border-white/10 text-center text-white/60">
                <p>&copy; {{ date('Y') }} His Kingdom Church. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
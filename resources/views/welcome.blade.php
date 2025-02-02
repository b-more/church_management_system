<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>His Kingdom Church</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Add Boxicons for improved icons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .transition-all {
            transition: all 0.3s ease-in-out;
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
    @include('layouts.navigation')

    <!-- Hero Section -->
    <section id="home" class="relative h-screen pt-16">
        <div class="absolute inset-0 bg-gradient-to-r from-primary/80 to-accent/80">
            <img src="{{ asset('images/apostle.png') }}" alt="Church Service"
                 class="w-full h-full object-cover mix-blend-overlay"
                 onerror="this.src='https://via.placeholder.com/1920x1080'">
        </div>
        <div class="relative h-full flex items-center justify-center text-center px-4">
            <div class="max-w-3xl">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">
                    Welcome to His Kingdom Church
                </h1>
                <p class="text-lg md:text-xl text-white/90 mb-8">
                    A place where God lives and visits other places
                </p>
                <a href="#contact"
                   class="inline-block bg-secondary text-white px-8 py-3 rounded-full text-lg font-semibold hover:bg-secondary/90 transition-colors">
                    Join Us This Sunday
                </a>
            </div>
        </div>
    </section>

    <!-- About Section with Enhanced Icons -->
    <section id="about" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-primary mb-4">About Us</h2>
                <div class="w-24 h-1 bg-secondary mx-auto"></div>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Who We Are -->
                <div class="bg-gray-50 p-8 rounded-lg text-center hover:shadow-lg transition-all">
                    <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class='bx bx-church text-3xl text-primary'></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Who We Are</h3>
                    <p class="text-gray-600">We are a multi-faceted, church planting, disciple making and teaching based ministry.</p>
                </div>

                <!-- Our Mandate -->
                <div class="bg-gray-50 p-8 rounded-lg text-center hover:shadow-lg transition-all">
                    <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class='bx bx-target-lock text-3xl text-primary'></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Our Mandate</h3>
                    <p class="text-gray-600">To raise leaders of kingdom influence at every level and in every sphere of life.</p>
                </div>

                <!-- Our Mission -->
                <div class="bg-gray-50 p-8 rounded-lg text-center hover:shadow-lg transition-all">
                    <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class='bx bx-world text-3xl text-primary'></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Our Mission</h3>
                    <p class="text-gray-600">To reach the lost for Christ, train them in the Word of God and send them as leaders.</p>
                </div>

                <!-- Our Motto -->
                <div class="bg-gray-50 p-8 rounded-lg text-center hover:shadow-lg transition-all">
                    <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class='bx bx-crown text-3xl text-primary'></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Our Motto</h3>
                    <p class="text-gray-600">"Raising Kingdom Ambassadors"</p>
                </div>

                <!-- Our Vision -->
                <div class="bg-gray-50 p-8 rounded-lg text-center hover:shadow-lg transition-all">
                    <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class='bx bx-search-alt text-3xl text-primary'></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Our Vision</h3>
                    <p class="text-gray-600">To be a global ministry which reaches out to the lost, plants churches, and makes disciples.</p>
                </div>

                <!-- Extended Vision -->
                <div class="bg-gray-50 p-8 rounded-lg text-center hover:shadow-lg transition-all">
                    <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class='bx bx-globe text-3xl text-primary'></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Extended Vision</h3>
                    <p class="text-gray-600">As a ministry, it is our vision to be present on every continent in a significant way.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-primary mb-4">Our Services</h2>
                <div class="w-24 h-1 bg-secondary mx-auto"></div>
            </div>
            <div class="grid md:grid-cols-2 gap-8">
                <div class="bg-white p-8 rounded-lg shadow-sm hover:shadow-lg transition-all">
                    <h3 class="text-2xl font-semibold text-primary mb-6">Sunday Service</h3>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <i class='bx bx-time text-xl text-secondary mr-3'></i>
                            <span>08:30 AM - 12:30 PM Main Service</span>
                        </div>
                        <div class="flex items-center">
                            <i class='bx bx-map text-xl text-secondary mr-3'></i>
                            <span>International Prayer Center (IPC)</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-lg shadow-sm hover:shadow-lg transition-all">
                    <h3 class="text-2xl font-semibold text-primary mb-6">Midweek Service</h3>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <i class='bx bx-time text-xl text-secondary mr-3'></i>
                            <span>Wednesday 5:30 PM - 7:00 PM</span>
                        </div>
                        <div class="flex items-center">
                            <i class='bx bx-map text-xl text-secondary mr-3'></i>
                            <span>International Prayer Center (IPC)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
<section id="contact" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-primary mb-4">Contact Us</h2>
            <div class="w-24 h-1 bg-secondary mx-auto"></div>
        </div>
        <div class="grid md:grid-cols-2 gap-12">
            <div>
                <h3 class="text-2xl font-semibold mb-6">Get in Touch</h3>
                <div class="space-y-6">
                    <div class="flex items-center">
                        <i class='bx bx-map text-2xl text-primary mr-4'></i>
                        <span>Meanwood Kwamwena Valley, Phase 1, Lusaka</span>
                    </div>
                    <div class="flex items-center">
                        <i class='bx bx-phone text-2xl text-primary mr-4'></i>
                        <span>+260 978124541</span>
                    </div>
                    <div class="flex items-center">
                        <i class='bx bx-envelope text-2xl text-primary mr-4'></i>
                        <span>info@hkc.church</span>
                    </div>
                </div>
            </div>

            <!-- Enhanced Contact Form with Error Handling and Success Message -->
            <form action="{{ route('contact.submit') }}" method="POST" class="space-y-6">
                @csrf

                @if(session('success'))
                    <div id="successNotification" class="fixed top-4 right-4 flex items-center bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-lg z-50">
                        <i class='bx bx-check-circle text-2xl mr-2'></i>
                        <div>
                            <p class="font-medium">{{ session('success') }}</p>
                            {{-- <p class="text-sm">We'll get back to you soon!</p> --}}
                        </div>
                        <button onclick="this.parentElement.style.display='none'" class="ml-4">
                            <i class='bx bx-x text-xl'></i>
                        </button>
                    </div>
                @endif

                <div>
                    <input type="text"
                           name="name"
                           placeholder="Your Name"
                           value="{{ old('name') }}"
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <input type="email"
                           name="email"
                           placeholder="Your Email"
                           value="{{ old('email') }}"
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <input type="tel"
                           name="phone"
                           placeholder="260 9XXXXXXXX"
                           value="{{ old('phone') }}"
                           oninput="
                               let value = this.value.replace(/\D/g, '');
                               if (!value.startsWith('260')) {
                                   value = '260' + value;
                               }
                               this.value = value.slice(0, 12);
                           "
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('phone') border-red-500 @enderror">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Format: 260 9XXXXXXXX</p>
                </div>

                <div>
                    <textarea name="message"
                              placeholder="Your Message"
                              rows="4"
                              class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('message') border-red-500 @enderror">{{ old('message') }}</textarea>
                    @error('message')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                        class="w-full bg-primary text-white py-3 rounded-lg hover:bg-primary/90 transition-all font-medium">
                    Send Message
                </button>
            </form>
        </div>
    </div>
</section>

    <!-- Footer -->
    @include('layouts.footer')

    <!-- Scroll to Top Button -->
    <button id="scrollToTop"
            class="fixed bottom-8 right-8 bg-primary text-white p-3 rounded-full shadow-lg hover:bg-primary/90 transition-all hidden">
        <i class='bx bx-up-arrow-alt text-2xl'></i>
    </button>

    <!-- JavaScript -->
    <script>
        // Mobile Menu Toggle
        const menuButton = document.getElementById('menuButton');
        const mobileMenu = document.getElementById('mobileMenu');

        menuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        // Scroll to Top Button
        const scrollToTop = document.getElementById('scrollToTop');

        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                scrollToTop.classList.remove('hidden');
            } else {
                scrollToTop.classList.add('hidden');
            }
        });

        scrollToTop.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        // Smooth Scrolling for Anchor Links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                target.scrollIntoView({ behavior: 'smooth' });
                mobileMenu.classList.add('hidden');
            });
        });
    </script>
    <script>
        // Auto-hide success notification
        document.addEventListener('DOMContentLoaded', function() {
            const notification = document.getElementById('successNotification');
            if (notification) {
                setTimeout(function() {
                    notification.style.opacity = '0';
                    notification.style.transform = 'translateY(-100%)';
                    setTimeout(function() {
                        notification.style.display = 'none';
                    }, 300);
                }, 5000); // Will hide after 5 seconds
            }
        });
    </script>
</body>
</html>

<!-- resources/views/welcome.blade.php -->
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
    @include('layouts.navigation')

    <!-- Hero Section -->
    <section id="home" class="relative h-screen pt-16">
        <div class="absolute inset-0 bg-gradient-to-r from-primary/80 to-accent/80">
            <!-- Replace with your church image -->
            <img src="{{ asset('images/pastors.jpg') }}" alt="Church Service" 
                 class="w-full h-full object-fit mix-blend-overlay"
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

    <!-- About Section -->
    <section id="about" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-primary mb-4">About Us</h2>
                <div class="w-24 h-1 bg-secondary mx-auto"></div>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-gray-50 p-8 rounded-lg text-center">
                    <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Who We Are</h3>
                    <p class="text-gray-600">We are a multi-faceted, church planting, disciple making and teaching based ministry.</p>
                </div>

                <div class="bg-gray-50 p-8 rounded-lg text-center">
                    <div class="w-16 h-16 bg-secondary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Our Mandate</h3>
                    <p class="text-gray-600">To raise leaders of kingdom influence at every level and in every sphere of life.</p>
                </div>

                <div class="bg-gray-50 p-8 rounded-lg text-center">
                    <div class="w-16 h-16 bg-accent/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Our Mission</h3>
                    <p class="text-gray-600">To reach the lost for Christ, train them in the Word of God and send them as leaders, representatives of Christ and ambassadors of Gods’ kingdom to their world.</p>
                </div>

                <div class="bg-gray-50 p-8 rounded-lg text-center">
                    <div class="w-16 h-16 bg-accent/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Our Motto</h3>
                    <p class="text-gray-600">“Raising Kingdom Ambassadors”</p>
                </div>

                <div class="bg-gray-50 p-8 rounded-lg text-center">
                    <div class="w-16 h-16 bg-accent/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Our Vision</h3>
                    <p class="text-gray-600">“To be a global ministry which reaches out to the lost, plants churches, makes disciples and raises leaders of global influence in their sphere of calling”.</p>
                </div>

                <div class="bg-gray-50 p-8 rounded-lg text-center">
                    <div class="w-16 h-16 bg-accent/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Extended Vision</h3>
                    <p class="text-gray-600">As a ministry, it is our vision to be present on every continent and in every country of the world in a significant and relevant way. </p>
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
                <div class="bg-white p-8 rounded-lg shadow-sm">
                    <h3 class="text-2xl font-semibold text-primary mb-6">Sunday Service</h3>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-secondary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>08:30 AM - 12:30 PM Main Service</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-secondary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>International Prayer Center (IPC)</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-lg shadow-sm">
                    <h3 class="text-2xl font-semibold text-primary mb-6">Midweek Service</h3>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-secondary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Wednesday 5:30 PM - 7:00 PM</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-secondary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
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
                        <svg class="w-6 h-6 text-primary mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>Meanwood Kwamwena Valley, Phase 1, Lusaka</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-primary mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <span>+260 978124541</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-primary mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span>info@hkc.church</span>
                        </div>
                    </div>
                </div>
                
                <form class="space-y-6" action="{{ route('contact.submit') }}" method="POST">
                    @csrf
                    <div>
                        <input type="text" name="name" placeholder="Your Name" required
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <input type="email" name="email" placeholder="Your Email" required
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <input type="phone" name="phone" placeholder="Your Phone number" required
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <textarea name="message" placeholder="Your Message" required rows="4"
                                  class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary"></textarea>
                    </div>
                    <button type="submit"
                            class="w-full bg-primary text-white py-3 rounded-lg hover:bg-primary/90 transition-colors">
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
            class="fixed bottom-8 right-8 bg-primary text-white p-3 rounded-full shadow-lg hover:bg-primary/90 transition-colors hidden">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
        </svg>
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
                mobileMenu.classList.add('hidden'); // Close mobile menu when link is clicked
            });
        });
    </script>
</body>
</html>
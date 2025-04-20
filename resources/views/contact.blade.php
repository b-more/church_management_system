<!-- resources/views/contact.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Contact His Kingdom Church - reach out to us with your questions or prayer requests.">
    <title>Contact Us - His Kingdom Church</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .text-shadow {
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
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
                    },
                    spacing: {
                        '128': '32rem',
                    },
                    boxShadow: {
                        'custom': '0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
                    }
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Document ready - no animation code
        });
    </script>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    @include('layouts.navigation')

    <!-- Hero Section -->
    <div class="relative pt-16 pb-32 flex content-center items-center justify-center min-h-[60vh]">
        <div class="absolute top-0 w-full h-full bg-center bg-cover bg-fixed" style="background-image: url('{{ asset('images/bg-contact.jpg') }}');">
            <span class="w-full h-full absolute opacity-80 bg-gradient-to-r from-primary/90 to-accent/70"></span>
        </div>
        <div class="container relative mx-auto px-4">
            <div class="items-center flex flex-wrap">
                <div class="w-full lg:w-8/12 px-4 ml-auto mr-auto text-center">
                    <div class="text-white">
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-4 text-shadow">Contact Us</h1>
                        <p class="mt-4 text-lg md:text-xl text-white/90">We'd love to hear from you. Reach out to us with any questions or prayer requests.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 w-full overflow-hidden leading-none">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none" class="h-16 w-full text-white">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V0C159,0,213,33.88,258.89,48.7,294.83,59.18,304.67,66.15,321.39,56.44Z" fill="currentColor"></path>
            </svg>
        </div>
    </div>

    <!-- Main Content -->
    <div class="relative py-12 md:py-20 bg-white">
        <div class="container mx-auto px-4 md:px-8">
            <div class="grid md:grid-cols-2 gap-16">
                <!-- Contact Information -->
                <div>
                    <div class="flex flex-col md:flex-row items-center mb-10">
                        <div class="w-20 h-1 bg-primary hidden md:block"></div>
                        <h2 class="text-3xl md:text-4xl font-bold text-primary md:ml-4">Get in Touch</h2>
                    </div>
                    <p class="text-gray-700 text-lg mb-8">
                        Reach out to us directly using the information below.
                    </p>

                    <div class="space-y-8">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1 w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center">
                                <i class="fas fa-map-marker-alt text-xl text-primary"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">Our Location</h3>
                                <p class="text-gray-600 mt-1">
                                    Meanwood Kwamwena Valley, Phase 1<br>
                                    Along Police Road, Lusaka<br>
                                    Zambia
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1 w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center">
                                <i class="fas fa-phone text-xl text-primary"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">Phone Numbers</h3>
                                <p class="text-gray-600 mt-1">
                                    +260 978 124 541<br>
                                    +260 978 353 364
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1 w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center">
                                <i class="fas fa-envelope text-xl text-primary"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">Email Address</h3>
                                <p class="text-gray-600 mt-1">
                                    info@hkc.co.zm
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1 w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center">
                                <i class="fas fa-clock text-xl text-primary"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">Service Times</h3>
                                <p class="text-gray-600 mt-1">
                                    Sunday: 8:30 AM - 12:30 PM<br>
                                    Wednesday: 5:30 PM - 7:00 PM
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div class="mt-12">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Connect With Us</h3>
                        <div class="flex space-x-4">
                            <a href="#" class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center hover:bg-primary hover:text-white transition-all">
                                <i class="fab fa-facebook-f text-lg"></i>
                            </a>
                            <a href="#" class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center hover:bg-primary hover:text-white transition-all">
                                <i class="fab fa-twitter text-lg"></i>
                            </a>
                            <a href="#" class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center hover:bg-primary hover:text-white transition-all">
                                <i class="fab fa-instagram text-lg"></i>
                            </a>
                            <a href="#" class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center hover:bg-primary hover:text-white transition-all">
                                <i class="fab fa-youtube text-lg"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="bg-gray-50 p-8 rounded-lg shadow-custom">
                    <h2 class="text-2xl font-bold text-primary mb-6">Send Us a Message</h2>

                    @if(session('success'))
                        <div id="successNotification" class="mb-6 flex items-center bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-lg">
                            <i class="fas fa-check-circle text-2xl mr-2"></i>
                            <div>
                                <p class="font-medium">{{ session('success') }}</p>
                            </div>
                            <button onclick="this.parentElement.style.display='none'" class="ml-auto">
                                <i class="fas fa-times text-lg"></i>
                            </button>
                        </div>
                    @endif

                    <form action="{{ route('contact.submit') }}" method="POST" class="space-y-6">
                        @csrf

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
        </div>
    </div>

    <!-- Google Map -->
    <div class="w-full h-96 mt-12">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3845.7486546372726!2d28.346883114051895!3d-15.346906421448663!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTXCsDIwJzQ4LjkiUyAyOMKwMjAnNTYuNyJF!5e0!3m2!1sen!2sus!4v1643898961532!5m2!1sen!2sus"
            width="100%"
            height="100%"
            style="border:0;"
            allowfullscreen=""
            loading="lazy">
        </iframe>
    </div>

    <!-- Prayer Request Section -->
    <div class="py-16 md:py-24 bg-gradient-to-r from-primary to-primary/90 text-white">
        <div class="container mx-auto px-4 text-center">
            <div class="inline-block bg-white/20 text-white font-bold px-4 py-1 rounded-full text-sm mb-4">We Are Here For You</div>
            <h2 class="text-3xl md:text-4xl font-bold mb-6">Need Prayer?</h2>
            <p class="text-white/90 max-w-2xl mx-auto mb-8 text-lg">
                Our prayer team is ready to pray with you and for you.
                Submit your prayer requests and we'll hold them in confidence.
            </p>
            <a href="#" class="inline-block bg-secondary text-primary px-8 py-3 rounded-lg text-lg font-semibold hover:bg-white transition-colors">
                Submit Prayer Request
            </a>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="text-center py-12 px-4 sm:px-6 lg:py-16 lg:px-8 bg-gray-50">
        <h2 class="text-3xl font-extrabold tracking-tight text-primary sm:text-4xl">
            <span class="block">Join us this Sunday</span>
        </h2>
        <p class="mt-4 text-lg leading-6 text-gray-600 max-w-3xl mx-auto">
            We'd love to have you worship with us. Experience the power of God's presence in our vibrant community.
        </p>
        <div class="mt-8 flex justify-center">
            <div class="inline-flex rounded-md shadow">
                <a href="/services" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-primary hover:bg-primary/90">
                    Our Services
                </a>
            </div>
            <div class="ml-3 inline-flex">
                <a href="/about" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-primary bg-white hover:bg-gray-100">
                    Learn More
                </a>
            </div>
        </div>
    </div>

    <!-- Scroll to Top Button -->
    <button id="scrollToTop"
            class="fixed bottom-8 right-8 bg-primary text-white p-3 rounded-full shadow-lg hover:bg-primary/90 transition-all hidden z-50">
        <i class="fas fa-arrow-up text-lg"></i>
    </button>

    <!-- Footer -->
    @include('layouts.footer')

    <script>
        // Auto-hide success notification
        document.addEventListener('DOMContentLoaded', function() {
            const notification = document.getElementById('successNotification');
            if (notification) {
                setTimeout(function() {
                    notification.style.opacity = '0';
                    notification.style.transform = 'translateY(-20px)';
                    setTimeout(function() {
                        notification.style.display = 'none';
                    }, 300);
                }, 5000); // Will hide after 5 seconds
            }
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();

                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    window.scrollTo({
                        top: target.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
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
    </script>
</body>
</html>

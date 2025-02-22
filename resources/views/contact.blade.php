@extends('layouts.app')

@section('title', 'Contact Us - His Kingdom Church')

@section('content')
<!-- Page Header -->
<div class="relative bg-gradient-to-r from-primary/80 to-accent/80 py-32">
    <img src="{{ asset('images/bg-contact.jpg') }}" alt="Contact Header" class="absolute inset-0 w-full h-full object-cover mix-blend-overlay">
    <div class="relative container mx-auto px-4 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Contact Us</h1>
        <p class="text-xl text-white/90 max-w-2xl mx-auto">
            We'd love to hear from you. Reach out to us with any questions or prayer requests.
        </p>
    </div>
</div>

<!-- Main Content -->
<section class="py-20">
    <div class="container mx-auto px-4">
        <div class="grid md:grid-cols-2 gap-16">
            <!-- Contact Information -->
            <div>
                <h2 class="text-3xl font-bold text-gray-900 mb-6">Get in Touch</h2>
                <p class="text-gray-600 mb-8">
                    Reach out to us directly using the information below.
                </p>

                <div class="space-y-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-1 w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center">
                            <i class='bx bx-map text-xl text-primary'></i>
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
                        <div class="flex-shrink-0 mt-1 w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center">
                            <i class='bx bx-phone text-xl text-primary'></i>
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
                        <div class="flex-shrink-0 mt-1 w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center">
                            <i class='bx bx-envelope text-xl text-primary'></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">Email Address</h3>
                            <p class="text-gray-600 mt-1">
                                info@hkc.co.zm
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-1 w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center">
                            <i class='bx bx-time text-xl text-primary'></i>
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
                        <a href="#" class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center hover:bg-primary hover:text-white transition-all">
                            <i class='bx bxl-facebook text-xl'></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center hover:bg-primary hover:text-white transition-all">
                            <i class='bx bxl-twitter text-xl'></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center hover:bg-primary hover:text-white transition-all">
                            <i class='bx bxl-instagram text-xl'></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center hover:bg-primary hover:text-white transition-all">
                            <i class='bx bxl-youtube text-xl'></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <form action="{{ route('contact.submit') }}" method="POST" class="space-y-6">
                @csrf

                @if(session('success'))
                    <div id="successNotification" class="fixed top-4 right-4 flex items-center bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-lg z-50">
                        <i class='bx bx-check-circle text-2xl mr-2'></i>
                        <div>
                            <p class="font-medium">{{ session('success') }}</p>
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

<!-- Google Map -->
<section class="py-0">
    <div class="w-full h-96">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3845.7486546372726!2d28.346883114051895!3d-15.346906421448663!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTXCsDIwJzQ4LjkiUyAyOMKwMjAnNTYuNyJF!5e0!3m2!1sen!2sus!4v1643898961532!5m2!1sen!2sus"
            width="100%"
            height="100%"
            style="border:0;"
            allowfullscreen=""
            loading="lazy">
        </iframe>
    </div>
</section>

<!-- Prayer Request Section -->
<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold text-primary mb-4">Need Prayer?</h2>
        <p class="text-gray-600 max-w-2xl mx-auto mb-8">
            Our prayer team is ready to pray with you and for you.
            Submit your prayer requests and we'll hold them in confidence.
        </p>
        <a href="#" class="inline-block bg-secondary text-white px-8 py-3 rounded-lg text-lg font-semibold hover:bg-secondary/90 transition-colors">
            Submit Prayer Request
        </a>
    </div>
</section>

<!-- Scroll to Top Button -->
<button id="scrollToTop"
        class="fixed bottom-8 right-8 bg-primary text-white p-3 rounded-full shadow-lg hover:bg-primary/90 transition-all hidden">
    <i class='bx bx-up-arrow-alt text-2xl'></i>
</button>
@endsection

@section('scripts')
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
@endsection

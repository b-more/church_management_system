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
    @include('layouts.navigation')

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

    <!-- Latest Services & Recordings -->
    <div class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-primary text-center mb-12">Latest Services & Messages</h2>
            
            @if($services->count() > 0)
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($services as $service)
                        <div class="bg-white rounded-lg overflow-hidden shadow-sm">
                            <!-- Service Banner -->
                            <div class="relative h-48 bg-gray-200">
                                @if($service->service_banner)
                                    <img src="{{ Storage::url($service->service_banner) }}" 
                                        alt="Service Banner"
                                        class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-primary/10">
                                        <svg class="w-12 h-12 text-primary/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                                
                                <!-- Date Overlay -->
                                <div class="absolute top-4 right-4 bg-primary text-white px-3 py-1 rounded-full text-sm">
                                    {{ $service->date->format('M d, Y') }}
                                </div>
                            </div>

                            <!-- Service Details -->
                            <div class="p-6">
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">
                                    {{ $service->message_title }}
                                </h3>
                                
                                <!-- Preacher Info -->
                                <div class="flex items-center mb-3 text-gray-600">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    <span>
                                        @if($service->preacher_type === 'visiting')
                                            {{ $service->visiting_preacher_name }}
                                            @if($service->visiting_preacher_church)
                                                <span class="text-sm text-gray-500">
                                                    ({{ $service->visiting_preacher_church }})
                                                </span>
                                            @endif
                                        @else
                                            {{ optional($service->preacher)->title }} 
                                            {{ optional($service->preacher)->first_name }} 
                                            {{ optional($service->preacher)->last_name }}
                                        @endif
                                    </span>
                                </div>

                                <!-- Bible Reading -->
                                @if($service->bible_reading)
                                    <div class="flex items-center mb-4 text-gray-600">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                        <span class="text-sm">{{ $service->bible_reading }}</span>
                                    </div>
                                @endif

                                <!-- Audio Player -->
                                @if($service->audio_recording)
                                    <div class="mt-4">
                                        <audio controls class="w-full">
                                            <source src="{{ Storage::url($service->audio_recording) }}" type="audio/mpeg">
                                            Your browser does not support the audio element.
                                        </audio>
                                    </div>
                                @endif

                                <!-- Stream Links -->
                                <div class="mt-4 flex gap-2">
                                    @if($service->facebook_stream_link)
                                        <a href="{{ $service->facebook_stream_link }}" 
                                        target="_blank"
                                        class="inline-flex items-center text-sm text-primary hover:text-primary/80">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                            </svg>
                                            Watch on Facebook
                                        </a>
                                    @endif

                                    @if($service->youtube_stream_link)
                                        <a href="{{ $service->youtube_stream_link }}" 
                                        target="_blank"
                                        class="inline-flex items-center text-sm text-primary hover:text-primary/80">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                            </svg>
                                            Watch on YouTube
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- View All Button -->
                @if($services->count() >= 6)
                    <div class="text-center mt-12">
                        <a href="{{ route('services.archive') }}" 
                        class="inline-block bg-primary text-white px-8 py-3 rounded-full hover:bg-primary/90 transition-colors">
                            View All Services
                        </a>
                    </div>
                @endif
            @else
                <div class="text-center text-gray-500">
                    <p>No service recordings available at the moment.</p>
                </div>
            @endif
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
    @include('layouts.footer')
</body>
</html>
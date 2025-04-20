@extends('layouts.app')

@section('title', $event->title)

@section('meta')
<meta property="og:title" content="{{ $event->title }}">
<meta property="og:description" content="{{ Str::limit($event->description, 150) }}">
@if($event->image_path)
<meta property="og:image" content="{{ asset(Storage::url($event->image_path)) }}">
@endif
<meta property="og:url" content="{{ route('events.show', $event->id) }}">
<meta property="og:type" content="article">
@endsection

@section('content')
<div class="relative pt-16 pb-32 flex content-center items-center justify-center min-h-[40vh]">
    <div class="absolute top-0 w-full h-full bg-center bg-cover bg-fixed"
        style="background-image: url('{{ asset('images/events-banner.jpg') }}');">
        <span class="w-full h-full absolute opacity-80 bg-gradient-to-r from-[#011EB7]/90 to-[#011EB7]/70"></span>
    </div>
    <div class="container relative mx-auto px-4">
        <div class="items-center flex flex-wrap">
            <div class="w-full lg:w-8/12 px-4 ml-auto mr-auto text-center">
                <div class="text-white">
                    <span class="inline-block bg-[#E0B041] text-white px-3 py-1 rounded-full text-sm font-medium mb-4">
                        {{ $event->event_type }}
                    </span>
                    <h1 class="text-4xl md:text-5xl font-bold mb-4 text-shadow">{{ $event->title }}</h1>
                    <p class="mt-4 text-lg md:text-xl text-white/90">
                        {{ $event->formatted_date_time }}
                    </p>
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
        <div class="grid md:grid-cols-3 gap-8 mb-12">
            <div class="md:col-span-2">
                <!-- Event Image - Full and Static -->
                @if($event->image_path)
                <div class="mb-8 bg-white rounded-lg shadow-custom overflow-hidden">
                    <img src="{{ asset(Storage::url($event->image_path)) }}"
                         alt="{{ $event->title }}"
                         class="w-full h-auto object-contain">
                </div>
                @endif

                <!-- Event Image - Reasonably Sized -->
                {{-- @if($event->image_path)
                <div class="mb-8 bg-white rounded-lg shadow-custom overflow-hidden">
                    <div class="flex justify-center p-4">
                        <img src="{{ asset(Storage::url($event->image_path)) }}"
                            alt="{{ $event->title }}"
                            class="max-w-full h-auto object-contain rounded-md"
                            style="max-height: 400px;"> <!-- Adds a reasonable maximum height -->
                    </div>
                </div>
                @endif --}}

                <div class="bg-white rounded-lg shadow-custom overflow-hidden p-6 md:p-8">
                    <!-- Event Details -->
                    <h2 class="text-2xl font-bold text-[#011EB7] mb-6">About This Event</h2>

                    <div class="prose max-w-none mb-8">
                        <p>{{ $event->description }}</p>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6 mb-8">
                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-[#011EB7]/10 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                                <i class="fas fa-map-marker-alt text-[#011EB7]"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800 mb-1">Venue</h3>
                                <p class="text-gray-600">{{ $event->venue }}</p>
                                @if($event->venue_address)
                                    <p class="text-gray-500 text-sm mt-1">{{ $event->venue_address }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-[#011EB7]/10 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                                <i class="fas fa-clock text-[#011EB7]"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800 mb-1">Date & Time</h3>
                                <p class="text-gray-600">
                                    {{ $event->start_date->format('F j, Y') }}
                                    @if($event->start_date->format('Y-m-d') != $event->end_date->format('Y-m-d'))
                                        - {{ $event->end_date->format('F j, Y') }}
                                    @endif
                                </p>
                                <p class="text-gray-500 text-sm mt-1">
                                    {{ date('g:i A', strtotime($event->start_time)) }} -
                                    {{ date('g:i A', strtotime($event->end_time)) }}
                                </p>
                            </div>
                        </div>

                        @if($event->organizer)
                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-[#011EB7]/10 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                                <i class="fas fa-user text-[#011EB7]"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800 mb-1">Organizer</h3>
                                <p class="text-gray-600">{{ $event->organizer->first_name }} {{ $event->organizer->last_name }}</p>
                            </div>
                        </div>
                        @endif

                        @if($event->department)
                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-[#011EB7]/10 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                                <i class="fas fa-sitemap text-[#011EB7]"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800 mb-1">Department</h3>
                                <p class="text-gray-600">{{ $event->department->name }}</p>
                            </div>
                        </div>
                        @endif
                    </div>

                    @if($event->registration_required)
                    <div class="border-t border-gray-200 pt-8 mt-8">
                        <h3 class="text-xl font-bold text-[#011EB7] mb-4">Registration Information</h3>

                        <div class="bg-[#011EB7]/5 p-4 rounded-lg mb-6">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-info-circle text-[#011EB7] mr-2"></i>
                                <p class="font-medium">Registration is required for this event.</p>
                            </div>

                            @if($event->registration_deadline)
                            <p class="text-gray-600 text-sm">
                                Registration deadline:
                                <span class="font-semibold">{{ $event->registration_deadline->format('F j, Y g:i A') }}</span>

                                @if($stats['daysLeft'] !== null)
                                    @if($stats['daysLeft'] > 0)
                                        <span class="text-[#E0B041] ml-2">({{ $stats['daysLeft'] }} days left)</span>
                                    @elseif($stats['daysLeft'] == 0)
                                        <span class="text-red-500 ml-2">(Last day to register!)</span>
                                    @else
                                        <span class="text-red-500 ml-2">(Registration closed)</span>
                                    @endif
                                @endif
                            </p>
                            @endif
                        </div>

                        <div class="flex justify-center">
                            @if(!$event->registration_deadline || now() <= $event->registration_deadline)
                                <a href="{{ route('events.register', $event->id) }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-[#011EB7] hover:bg-[#011EB7]/90 transition-colors">
                                    Register Now
                                </a>
                            @else
                                <button disabled class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-gray-400 cursor-not-allowed">
                                    Registration Closed
                                </button>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="md:col-span-1">
                <!-- Registration Stats -->
                @if($event->registration_required)
                <div class="bg-white rounded-lg shadow-custom overflow-hidden p-6 mb-6">
                    <h3 class="text-xl font-bold text-[#011EB7] mb-4">Registration Stats</h3>

                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-gray-600 text-sm">Registrations</span>
                                <span class="text-gray-800 font-semibold">{{ $stats['totalRegistrations'] }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-[#011EB7] h-2.5 rounded-full" style="width: {{ $stats['registrationPercentage'] }}%"></div>
                            </div>
                            <div class="flex justify-between items-center mt-1">
                                <span class="text-xs text-gray-500">0</span>
                                <span class="text-xs text-gray-500">{{ $event->expected_attendance }}</span>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-gray-600 text-sm">Confirmed</span>
                                <span class="text-gray-800 font-semibold">{{ $stats['confirmedRegistrations'] }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                @php
                                    $confirmedPercentage = $event->expected_attendance ? min(100, round(($stats['confirmedRegistrations'] / $event->expected_attendance) * 100)) : 0;
                                @endphp
                                <div class="bg-[#E0B041] h-2.5 rounded-full" style="width: {{ $confirmedPercentage }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Event Quick Info -->
                <div class="bg-white rounded-lg shadow-custom overflow-hidden p-6">
                    <h3 class="text-xl font-bold text-[#011EB7] mb-4">Quick Info</h3>

                    <ul class="space-y-3">
                        <li class="flex items-center text-gray-600">
                            <i class="fas fa-calendar-alt text-[#011EB7] mr-3 w-5"></i>
                            <span>{{ $event->start_date->format('F j, Y') }}</span>
                        </li>
                        <li class="flex items-center text-gray-600">
                            <i class="fas fa-clock text-[#011EB7] mr-3 w-5"></i>
                            <span>{{ date('g:i A', strtotime($event->start_time)) }}</span>
                        </li>
                        <li class="flex items-center text-gray-600">
                            <i class="fas fa-map-marker-alt text-[#011EB7] mr-3 w-5"></i>
                            <span>{{ $event->venue }}</span>
                        </li>
                        <li class="flex items-center text-gray-600">
                            <i class="fas fa-tag text-[#011EB7] mr-3 w-5"></i>
                            <span>{{ $event->event_type }}</span>
                        </li>
                        <li class="flex items-center text-gray-600">
                            <i class="fas fa-users text-[#011EB7] mr-3 w-5"></i>
                            <span>Expected: {{ $event->expected_attendance ?? 'N/A' }}</span>
                        </li>
                    </ul>

                    <!-- Share Links -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="font-semibold text-gray-700 mb-3">Share This Event</h4>
                        <div class="flex space-x-2">
                            <a href="https://wa.me/?text={{ urlencode($event->title . ' - ' . route('events.show', $event->id)) }}" target="_blank" class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center hover:bg-green-600 transition-colors">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('events.show', $event->id)) }}" target="_blank" class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center hover:bg-blue-700 transition-colors">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <button onclick="copyToClipboard('{{ route('events.show', $event->id) }}')" class="w-8 h-8 bg-gray-200 text-gray-700 rounded-full flex items-center justify-center hover:bg-gray-300 transition-colors">
                                <i class="fas fa-link"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Back to Events -->
        <div class="flex justify-center">
            <a href="{{ route('events.index') }}" class="inline-flex items-center text-[#011EB7] font-semibold hover:text-[#011EB7]/80 transition-colors">
                <i class="fas fa-arrow-left mr-2 text-sm"></i>
                Back to all events
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function copyToClipboard(text) {
        const el = document.createElement('textarea');
        el.value = text;
        document.body.appendChild(el);
        el.select();
        document.execCommand('copy');
        document.body.removeChild(el);

        // Show a notification
        alert('Link copied to clipboard!');
    }
</script>
@endpush
@endsection

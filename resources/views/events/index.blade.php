@extends('layouts.app')

@section('title', 'Church Events')

@section('content')
<div class="relative pt-16 pb-32 flex content-center items-center justify-center min-h-[40vh]">
    <div class="absolute top-0 w-full h-full bg-center bg-cover bg-fixed" style="background-image: url('/images/events-banner.jpg');">
        <span class="w-full h-full absolute opacity-80 bg-gradient-to-r from-[#011EB7]/90 to-[#011EB7]/70"></span>
    </div>
    <div class="container relative mx-auto px-4">
        <div class="items-center flex flex-wrap">
            <div class="w-full lg:w-8/12 px-4 ml-auto mr-auto text-center">
                <div class="text-white">
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-4 text-shadow">Church Events</h1>
                    <p class="mt-4 text-lg md:text-xl text-white/90">Join us for our upcoming services and activities</p>
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
        <!-- Upcoming Events Section -->
        <div class="mb-16">
            <div class="flex flex-col md:flex-row items-center mb-10">
                <div class="w-20 h-1 bg-[#011EB7] hidden md:block"></div>
                <h2 class="text-3xl md:text-4xl font-bold text-[#011EB7] md:ml-4">Upcoming Events</h2>
            </div>

            @if(count($upcomingEvents) > 0)
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($upcomingEvents as $event)
                        <div class="bg-white rounded-lg shadow-custom overflow-hidden hover:shadow-lg transition-all transform hover:-translate-y-1">
                            <div class="h-48 overflow-hidden relative">
                                @if($event->image_path)
                                    <img src="{{ Storage::url($event->image_path) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-r from-[#011EB7]/90 to-[#011EB7]/70 flex items-center justify-center">
                                        <i class="fas fa-calendar-alt text-white text-5xl"></i>
                                    </div>
                                @endif
                                <div class="absolute top-0 right-0 bg-[#E0B041] text-white px-3 py-1 m-2 rounded-lg text-sm font-semibold">
                                    {{ $event->event_type }}
                                </div>
                            </div>

                            <div class="p-6">
                                <div class="flex items-center mb-2 text-sm text-gray-500">
                                    <i class="fas fa-calendar-day mr-2 text-[#011EB7]"></i>
                                    <span>{{ $event->start_date->format('F j, Y') }}</span>
                                    @if($event->start_date->format('Y-m-d') != $event->end_date->format('Y-m-d'))
                                        <span class="mx-1">-</span>
                                        <span>{{ $event->end_date->format('F j, Y') }}</span>
                                    @endif
                                </div>

                                <div class="flex items-center mb-4 text-sm text-gray-500">
                                    <i class="fas fa-map-marker-alt mr-2 text-[#011EB7]"></i>
                                    <span>{{ $event->venue }}</span>
                                </div>

                                <h3 class="text-xl font-bold text-[#011EB7] mb-2">{{ $event->title }}</h3>

                                <p class="text-gray-600 mb-6 text-sm line-clamp-2">
                                    {{ Str::limit($event->description, 100) }}
                                </p>

                                <div class="flex justify-between items-center">
                                    <a href="{{ route('events.show', $event->id) }}" class="inline-flex items-center text-[#011EB7] font-semibold hover:text-[#011EB7]/80 transition-colors">
                                        View Details
                                        <i class="fas fa-arrow-right ml-2 text-sm"></i>
                                    </a>

                                    @if($event->registration_required)
                                        <span class="text-xs text-[#E0B041] font-semibold">Registration Required</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-12 text-center">
                    <div class="w-20 h-20 bg-[#011EB7]/10 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-calendar-times text-[#011EB7] text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">No Upcoming Events</h3>
                    <p class="text-gray-500">Check back later for new events.</p>
                </div>
            @endif
        </div>

        <!-- Past Events Section -->
        @if(count($pastEvents) > 0)
            <div class="mb-16">
                <div class="flex flex-col md:flex-row items-center mb-10">
                    <div class="w-20 h-1 bg-[#011EB7] hidden md:block"></div>
                    <h2 class="text-3xl md:text-4xl font-bold text-[#011EB7] md:ml-4">Past Events</h2>
                </div>

                <div class="grid md:grid-cols-3 gap-6">
                    @foreach($pastEvents as $event)
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-all p-4 flex items-center">
                            <div class="w-16 h-16 bg-[#011EB7]/10 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                                <i class="fas fa-calendar-check text-[#011EB7] text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-[#011EB7]">{{ $event->title }}</h3>
                                <p class="text-sm text-gray-500">{{ $event->start_date->format('F j, Y') }}</p>
                                <a href="{{ route('events.show', $event->id) }}" class="text-xs text-[#011EB7] hover:underline mt-1 inline-block">View Details</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

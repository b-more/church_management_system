@extends('layouts.app')

@section('title', 'Registration Confirmed - ' . $event->title)

@section('content')
<div class="relative pt-16 pb-32 flex content-center items-center justify-center min-h-[40vh]">
    <div class="absolute top-0 w-full h-full bg-center bg-cover bg-fixed"
        style="background-image: url('{{ $event->image_path ? Storage::url($event->image_path) : '/images/events-banner.jpg' }}');">
        <span class="w-full h-full absolute opacity-80 bg-gradient-to-r from-[#011EB7]/90 to-[#011EB7]/70"></span>
    </div>
    <div class="container relative mx-auto px-4">
        <div class="items-center flex flex-wrap">
            <div class="w-full lg:w-8/12 px-4 ml-auto mr-auto text-center">
                <div class="text-white">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4 text-shadow">Registration Confirmed!</h1>
                    <p class="mt-4 text-lg md:text-xl text-white/90">
                        Your registration for {{ $event->title }} has been successful.
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
        <div class="max-w-3xl mx-auto">
            @if(session('success'))
                <div class="mb-8 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-lg">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-2xl"></i>
                        </div>
                        <div class="ml-3">
                            <p class="font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white shadow-custom rounded-lg overflow-hidden p-6 md:p-8">
                <div class="flex flex-col items-center text-center mb-8">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-check text-green-500 text-3xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Registration Successful!</h2>
                    <p class="text-gray-600">Thank you for registering for this event. Your registration has been confirmed.</p>
                </div>

                <div class="bg-gray-50 rounded-lg p-6 mb-8">
                    <h3 class="text-xl font-semibold text-[#011EB7] mb-4">Registration Details</h3>

                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">Registration Number</p>
                                <p class="font-semibold">{{ $registration->registration_number }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Registration Date</p>
                                <p class="font-semibold">{{ $registration->registered_at->format('F j, Y') }}</p>
                            </div>
                        </div>

                        <div class="border-t border-gray-200 pt-4">
                            <p class="text-sm text-gray-500">Event</p>
                            <p class="font-semibold">{{ $event->title }}</p>
                            <p class="text-gray-600 text-sm mt-1">
                                {{ $event->start_date->format('F j, Y') }} •
                                {{ date('g:i A', strtotime($event->start_time)) }} -
                                {{ date('g:i A', strtotime($event->end_time)) }}
                            </p>
                            <p class="text-gray-600 text-sm mt-1">{{ $event->venue }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-[#011EB7]/5 p-6 rounded-lg mb-8">
                    <h3 class="text-lg font-semibold text-[#011EB7] mb-3">Important Information</h3>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-[#011EB7] mt-1 mr-2"></i>
                            <span>Please arrive 15 minutes before the event starts.</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-[#011EB7] mt-1 mr-2"></i>
                            <span>Bring your registration number for quick check-in.</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-[#011EB7] mt-1 mr-2"></i>
                            <span>If you have any questions, please contact the event organizer.</span>
                        </li>
                    </ul>
                </div>

                <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4">
                    <a href="{{ route('events.show', $event->id) }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-[#011EB7] hover:bg-[#011EB7]/90 transition-colors">
                        Back to Event
                    </a>

                    <a href="{{ route('events.index') }}" class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        Browse Other Events
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

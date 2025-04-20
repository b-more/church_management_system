@extends('layouts.app')

@section('title', 'Register for ' . $event->title)

@section('content')
<div class="relative pt-16 pb-32 flex content-center items-center justify-center min-h-[40vh]">
    <div class="absolute top-0 w-full h-full bg-center bg-cover bg-fixed"
        style="background-image: url('{{ $event->image_path ? asset(Storage::url($event->image_path)) : asset('images/events-banner.jpg') }}');">
        <span class="w-full h-full absolute opacity-80 bg-gradient-to-r from-[#011EB7]/90 to-[#011EB7]/70"></span>
    </div>
    <div class="container relative mx-auto px-4">
        <div class="items-center flex flex-wrap">
            <div class="w-full lg:w-8/12 px-4 ml-auto mr-auto text-center">
                <div class="text-white">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4 text-shadow">Registration Form</h1>
                    <p class="mt-4 text-lg md:text-xl text-white/90">
                        {{ $event->title }}
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
            <div class="bg-white shadow-custom rounded-lg overflow-hidden p-6 md:p-8">
                <div class="mb-8 pb-6 border-b border-gray-200">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-[#011EB7]/10 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-calendar-check text-[#011EB7] text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-[#011EB7]">{{ $event->title }}</h2>
                            <p class="text-gray-600">{{ $event->formatted_date_time }}</p>
                        </div>
                    </div>

                    <div class="bg-[#011EB7]/5 p-4 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-[#011EB7] mt-1 mr-3"></i>
                            <div>
                                <p class="font-medium">Please fill out the form below to register for this event.</p>
                                <p class="text-gray-600 text-sm mt-1">All fields marked with an asterisk (*) are required.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Registration Form -->
                <form action="{{ route('events.registration.store', $event->id) }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                        <input type="text"
                               name="full_name"
                               id="full_name"
                               value="{{ old('full_name') }}"
                               required
                               placeholder="Enter your full name"
                               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#011EB7]/20 focus:border-[#011EB7] transition-all @error('full_name') border-red-500 @enderror">
                        @error('full_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label for="attendance_status" class="block text-sm font-medium text-gray-700 mb-1">Attendance Status *</label>
                            <select name="attendance_status"
                                   id="attendance_status"
                                   required
                                   class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#011EB7]/20 focus:border-[#011EB7] transition-all @error('attendance_status') border-red-500 @enderror">
                                <option value="Confirmed attending" {{ old('attendance_status') == 'Confirmed attending' ? 'selected' : '' }}>Confirmed attending</option>
                                <option value="Coming but not sure" {{ old('attendance_status') == 'Coming but not sure' ? 'selected' : '' }}>Coming but not sure</option>
                            </select>
                            @error('attendance_status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number *</label>
                            <input type="tel"
                                   name="phone"
                                   id="phone"
                                   value="{{ old('phone') }}"
                                   required
                                   placeholder="e.g. 260 9XXXXXXXX"
                                   class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#011EB7]/20 focus:border-[#011EB7] transition-all @error('phone') border-red-500 @enderror">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="special_requirements" class="block text-sm font-medium text-gray-700 mb-1">Special Requirements or Requests (Optional)</label>
                        <textarea name="special_requirements"
                                  id="special_requirements"
                                  rows="3"
                                  class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#011EB7]/20 focus:border-[#011EB7] transition-all @error('special_requirements') border-red-500 @enderror">{{ old('special_requirements') }}</textarea>
                        @error('special_requirements')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full bg-[#011EB7] text-white py-3 rounded-lg hover:bg-[#011EB7]/90 transition-all font-medium">
                            Register for Event
                        </button>
                    </div>
                </form>
            </div>

            <!-- Back to Event -->
            <div class="mt-6 text-center">
                <a href="{{ route('events.show', $event->id) }}" class="inline-flex items-center text-[#011EB7] font-semibold hover:text-[#011EB7]/80 transition-colors">
                    <i class="fas fa-arrow-left mr-2 text-sm"></i>
                    Back to event details
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

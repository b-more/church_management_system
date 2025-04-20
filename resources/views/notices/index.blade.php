@extends('layouts.app')

@section('title', 'Church Notices')

@section('content')
<div class="relative pt-16 pb-32 flex content-center items-center justify-center min-h-[40vh]">
    <div class="absolute top-0 w-full h-full bg-center bg-cover bg-fixed" style="background-image: url('/images/notices-banner.jpg');">
        <span class="w-full h-full absolute opacity-80 bg-gradient-to-r from-[#011EB7]/90 to-[#011EB7]/70"></span>
    </div>
    <div class="container relative mx-auto px-4">
        <div class="items-center flex flex-wrap">
            <div class="w-full lg:w-8/12 px-4 ml-auto mr-auto text-center">
                <div class="text-white">
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-4 text-shadow">Church Notices</h1>
                    <p class="mt-4 text-lg md:text-xl text-white/90">Important announcements from His Kingdom Church</p>
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
        <div class="mb-16">
            <div class="flex flex-col md:flex-row items-center mb-10">
                <div class="w-20 h-1 bg-[#011EB7] hidden md:block"></div>
                <h2 class="text-3xl md:text-4xl font-bold text-[#011EB7] md:ml-4">Latest Notices</h2>
            </div>

            @if(count($notices) > 0)
                <div class="space-y-6">
                    @foreach($notices as $notice)
                        <div class="bg-white shadow-custom rounded-lg overflow-hidden hover:shadow-lg transition-all">
                            <div class="p-6">
                                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4">
                                    <div>
                                        <span class="text-sm text-gray-500">{{ $notice->date->format('F d, Y') }}</span>
                                        <span class="mx-2 text-gray-300">|</span>
                                        <span class="text-sm text-[#E0B041] font-medium">To: {{ Str::title(str_replace('_', ' ', $notice->recipient_group)) }}</span>
                                    </div>
                                    <div class="mt-2 md:mt-0">
                                        <span class="text-sm text-gray-500">
                                            <i class="fas fa-eye text-gray-400 mr-1"></i> {{ $notice->view_count }} views
                                        </span>
                                    </div>
                                </div>

                                <h3 class="text-xl font-bold text-[#011EB7] mb-3">{{ $notice->title }}</h3>

                                <div class="grid md:grid-cols-3 gap-6">
                                    <div class="md:col-span-2">
                                        <p class="text-gray-600 mb-4">{{ $notice->getExcerpt(200) }}</p>
                                        <a href="{{ route('notices.show', $notice->id) }}" class="inline-flex items-center text-[#011EB7] font-semibold hover:text-[#011EB7]/80 transition-colors">
                                            Read more
                                            <i class="fas fa-arrow-right ml-2 text-sm"></i>
                                        </a>
                                    </div>

                                    @if($notice->image_path)
                                        <div class="md:col-span-1">
                                            <img src="{{ Storage::url($notice->image_path) }}" alt="{{ $notice->title }}" class="w-full h-40 object-cover rounded-lg">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $notices->links() }}
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-12 text-center">
                    <div class="w-20 h-20 bg-[#011EB7]/10 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-bell-slash text-[#011EB7] text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">No Notices Available</h3>
                    <p class="text-gray-500">Check back later for new announcements.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

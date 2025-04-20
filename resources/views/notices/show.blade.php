@extends('layouts.app')

@section('title', $notice->title)

@section('meta')
<meta property="og:title" content="{{ $notice->title }}">
<meta property="og:description" content="{{ $notice->getExcerpt(150) }}">
@if($notice->image_path)
<meta property="og:image" content="{{ Storage::url($notice->image_path) }}">
@endif
<meta property="og:url" content="{{ route('notices.show', $notice->id) }}">
<meta property="og:type" content="article">
@endsection

@section('content')
<div class="relative pt-16 pb-32 flex content-center items-center justify-center min-h-[40vh]">
    <div class="absolute top-0 w-full h-full bg-center bg-cover bg-fixed" style="background-image: url('/images/notices-banner.jpg');">
        <span class="w-full h-full absolute opacity-80 bg-gradient-to-r from-[#011EB7]/90 to-[#011EB7]/70"></span>
    </div>
    <div class="container relative mx-auto px-4">
        <div class="items-center flex flex-wrap">
            <div class="w-full lg:w-8/12 px-4 ml-auto mr-auto text-center">
                <div class="text-white">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4 text-shadow">{{ $notice->title }}</h1>
                    <p class="mt-4 text-lg md:text-xl text-white/90">{{ $notice->date->format('F d, Y') }}</p>
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
        <div class="max-w-4xl mx-auto">
            <div class="bg-white shadow-custom rounded-lg overflow-hidden p-6 md:p-8">
                <!-- Notice Header -->
                <div class="mb-6 pb-6 border-b border-gray-200">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                        <div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-[#011EB7]/10 text-[#011EB7]">
                                To: {{ Str::title(str_replace('_', ' ', $notice->recipient_group)) }}
                            </span>
                            <p class="mt-2 text-gray-500">
                                <i class="fas fa-calendar-alt mr-1"></i> {{ $notice->date->format('F d, Y') }}
                                <span class="mx-2">•</span>
                                <i class="fas fa-eye mr-1"></i> {{ $notice->view_count }} views
                            </p>
                        </div>

                        <div class="mt-4 md:mt-0 flex space-x-2">
                            <a href="{{ $shareLinks['whatsapp'] }}" target="_blank" class="w-10 h-10 bg-green-500 text-white rounded-full flex items-center justify-center hover:bg-green-600 transition-colors">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                            <a href="{{ $shareLinks['facebook'] }}" target="_blank" class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center hover:bg-blue-700 transition-colors">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <button onclick="copyToClipboard('{{ route('notices.show', $notice->id) }}')" class="w-10 h-10 bg-gray-200 text-gray-700 rounded-full flex items-center justify-center hover:bg-gray-300 transition-colors">
                                <i class="fas fa-link"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Notice Image -->
                @if($notice->image_path)
                <div class="mb-8">
                    <img src="{{ Storage::url($notice->image_path) }}" alt="{{ $notice->title }}" class="w-full rounded-lg shadow-sm">
                </div>
                @endif

                <!-- Notice Content -->
                <div class="prose max-w-none">
                    {!! $notice->body !!}
                </div>

                <!-- Back to Notices -->
                <div class="mt-10 pt-6 border-t border-gray-200">
                    <a href="{{ route('notices.index') }}" class="inline-flex items-center text-[#011EB7] font-semibold hover:text-[#011EB7]/80 transition-colors">
                        <i class="fas fa-arrow-left mr-2 text-sm"></i>
                        Back to all notices
                    </a>
                </div>
            </div>
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

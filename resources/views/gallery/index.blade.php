@extends('layouts.app')

@section('content')
<div class="bg-white shadow-lg rounded-lg overflow-hidden">
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold text-primary">
                Photo Gallery
            </h2>
            @auth
                @if(auth()->user()->can('upload_images'))
                <a href="{{ route('gallery.upload') }}"
                   class="inline-flex items-center px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary/90
                          transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 4v16m8-8H4"/>
                    </svg>
                    Upload New Images
                </a>
                @endif
            @endauth
        </div>

        <div class="bg-gray-50 rounded-lg p-6">
            <!-- React Gallery Component Mount Point -->
            <div id="gallery-root"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        window.galleryImages = @json($images);
    </script>
@endpush

@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<div class="relative pt-16 pb-32 flex content-center items-center justify-center min-h-[40vh]">
    <div class="absolute top-0 w-full h-full bg-center bg-cover bg-fixed" style="background-image: url('/images/gallery-banner.jpg');">
        <span class="w-full h-full absolute opacity-80 bg-primary bg-gradient-to-r from-primary/90 to-primary/70"></span>
    </div>
    <div class="container relative mx-auto px-4">
        <div class="items-center flex flex-wrap">
            <div class="w-full lg:w-8/12 px-4 ml-auto mr-auto text-center">
                <div class="text-white">
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-4 text-shadow">Our Gallery</h1>
                    <p class="mt-4 text-lg md:text-xl text-white/90">Moments and memories from His Kingdom Church</p>
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
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="p-6">
                <div class="flex flex-col md:flex-row justify-between items-center mb-6">
                    <div class="flex items-center mb-4 md:mb-0">
                        <div class="w-10 h-1 bg-primary hidden md:block"></div>
                        <h2 class="text-3xl font-bold text-primary md:ml-4">
                            Photo Gallery
                        </h2>
                    </div>
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

                <!-- Scrollable Gallery Container - Fixed Height for 3 Rows -->
                <div class="gallery-container overflow-y-auto rounded-lg" style="scrollbar-width: thin; scrollbar-color: #011EB7 #f3f4f6; overflow-y: auto;">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($images as $index => $image)
                            <div class="relative group cursor-pointer overflow-hidden rounded-lg shadow-lg aspect-square"
                                 onclick="openImageModal({{ $index }})">
                                <img src="{{ $image['image_url'] }}"
                                     alt="{{ $image['alt_text'] ?? 'Gallery image' }}"
                                     class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110">
                                <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-25 transition-opacity duration-300"></div>
                                <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <h3 class="text-white font-semibold">{{ $image['title'] ?? 'Gallery image' }}</h3>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                @if(count($images) == 0)
                    <div class="text-center py-8">
                        <p class="text-gray-500">No images available in the gallery.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Ultra Simple Modal - Maximum Compatibility -->
<div id="gallery-modal" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background-color:rgba(0,0,0,0.9); z-index:9999; align-items:center; justify-content:center;" onclick="closeImageModal()">
    <!-- Close Button -->
    <div style="position:absolute; top:20px; right:20px; cursor:pointer; color:white; z-index:10001;" onclick="closeImageModal(); event.stopPropagation();">
        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
    </div>

    <!-- Navigation Buttons with Better Visibility -->
    <div style="position:absolute; top:50%; left:20px; transform:translateY(-50%); cursor:pointer; color:white; background-color:rgba(0,0,0,0.5); border-radius:50%; padding:10px; z-index:10001;"
         onclick="navigatePrev(); event.stopPropagation();">
        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="15 18 9 12 15 6"></polyline>
        </svg>
    </div>

    <div style="position:absolute; top:50%; right:20px; transform:translateY(-50%); cursor:pointer; color:white; background-color:rgba(0,0,0,0.5); border-radius:50%; padding:10px; z-index:10001;"
         onclick="navigateNext(); event.stopPropagation();">
        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="9 18 15 12 9 6"></polyline>
        </svg>
    </div>

    <!-- Modal Content with Perfect Centering -->
    <div style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); width:80%; max-height:80%; text-align:center; z-index:10000;" onclick="event.stopPropagation();">
        <img id="modal-image" src="" style="max-width:100%; max-height:70vh; border-radius:4px; object-fit:contain;">
        <div style="margin-top:20px; color:white; text-align:center;">
            <h2 id="modal-title" style="font-size:24px; font-weight:bold;"></h2>
            <p id="modal-description" style="margin-top:8px; opacity:0.8;"></p>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .text-shadow {
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }

    /* Fixed height gallery container for exactly 3 rows */
    .gallery-container {
        /* Fixed explicit height that will show exactly 3 rows */
        height: 600px;
        max-height: 80vh;
        overflow-y: auto;
    }

    /* Responsive adjustments for different screen sizes */
    @media (max-width: 1023px) {
        .gallery-container {
            height: 500px;
        }
    }

    @media (max-width: 767px) {
        .gallery-container {
            height: 400px;
        }
    }

    /* Custom scrollbar styling */
    .gallery-container::-webkit-scrollbar {
        width: 8px;
    }

    .gallery-container::-webkit-scrollbar-track {
        background: #f3f4f6;
        border-radius: 4px;
    }

    .gallery-container::-webkit-scrollbar-thumb {
        background-color: #011EB7;
        border-radius: 4px;
    }

    /* Use aspect-square for consistent image sizes */
    .aspect-square {
        aspect-ratio: 1 / 1;
    }
</style>
@endsection

<!-- Self-contained JavaScript with no dependencies -->
@push('scripts')
<script>
    // Wait for page to load before setting up gallery
    document.addEventListener('DOMContentLoaded', function() {
        // Global variables for navigation
        window.galleryImages = @json($images);
        window.currentImageIndex = 0;

        // Log loading of script and images
        console.log('Gallery script loaded');
        console.log('Found ' + window.galleryImages.length + ' images');

        // Add keyboard navigation
        document.addEventListener('keydown', function(e) {
            // Check if modal is visible
            if (document.getElementById('gallery-modal').style.display === 'flex') {
                console.log('Key pressed in modal:', e.key);
                if (e.key === 'Escape') {
                    closeImageModal();
                } else if (e.key === 'ArrowRight') {
                    navigateNext();
                } else if (e.key === 'ArrowLeft') {
                    navigatePrev();
                }
            }
        });
    });

    // Opens the modal with a specific image
    function openImageModal(index) {
        console.log('Opening modal with image index:', index);
        window.currentImageIndex = index;
        var modal = document.getElementById('gallery-modal');
        var modalImage = document.getElementById('modal-image');
        var modalTitle = document.getElementById('modal-title');
        var modalDescription = document.getElementById('modal-description');

        // Update modal content
        updateModalContent();

        // Show modal
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    // Close the modal
    function closeImageModal() {
        console.log('Closing modal');
        document.getElementById('gallery-modal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    // Navigate to next image
    function navigateNext() {
        console.log('Navigating to next image');
        window.currentImageIndex = (window.currentImageIndex + 1) % window.galleryImages.length;
        updateModalContent();
        event.stopPropagation();
    }

    // Navigate to previous image
    function navigatePrev() {
        console.log('Navigating to previous image');
        window.currentImageIndex = (window.currentImageIndex - 1 + window.galleryImages.length) % window.galleryImages.length;
        updateModalContent();
        event.stopPropagation();
    }

    // Update the modal content based on current index
    function updateModalContent() {
        var image = window.galleryImages[window.currentImageIndex];
        console.log('Updating modal to show image:', image.title);

        document.getElementById('modal-image').src = image.image_url;
        document.getElementById('modal-title').textContent = image.title || 'Gallery image';
        document.getElementById('modal-description').textContent = image.alt_text || '';
    }
</script>
@endpush

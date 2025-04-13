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

                <!-- Gallery Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($images as $index => $image)
                        <div class="relative group cursor-pointer overflow-hidden rounded-lg shadow-lg h-64">
                            <!-- Use data attributes to store image info for the modal -->
                            <img
                                src="{{ $image['image_url'] }}"
                                alt="{{ $image['alt_text'] ?? 'Gallery image' }}"
                                class="w-full h-64 object-cover transition-transform duration-300 group-hover:scale-110"
                                onclick="openImageModal('{{ $image['image_url'] }}', '{{ $image['title'] ?? 'Gallery image' }}', '{{ $image['alt_text'] ?? '' }}', {{ $index }})"
                            >
                            <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-25 transition-opacity duration-300"></div>
                            <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <h3 class="text-white font-semibold">{{ $image['title'] ?? 'Gallery image' }}</h3>
                            </div>
                        </div>
                    @endforeach
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

<!-- Simple Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-90 hidden z-50" onclick="closeImageModal()">
    <div class="absolute top-5 right-5">
        <button onclick="closeImageModal()" class="text-white hover:text-gray-300 focus:outline-none">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <div class="absolute top-1/2 left-5">
        <button onclick="prevImage()" class="text-white bg-black/50 p-2 rounded-full hover:bg-black/70 focus:outline-none">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>
    </div>

    <div class="absolute top-1/2 right-5">
        <button onclick="nextImage()" class="text-white bg-black/50 p-2 rounded-full hover:bg-black/70 focus:outline-none">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>
    </div>

    <div class="flex items-center justify-center h-full" onclick="event.stopPropagation()">
        <div class="max-w-4xl mx-auto p-4">
            <img id="modalImg" src="" alt="Enlarged view" class="max-h-[80vh] max-w-full">
            <div class="text-white text-center mt-4">
                <h3 id="modalTitle" class="text-xl font-bold"></h3>
                <p id="modalDescription" class="text-gray-300 mt-2"></p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .text-shadow {
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }
</style>
@endpush

@push('scripts')
<script>
    // Store all images for navigation
    const allImages = @json($images);
    let currentImageIndex = 0;

    function openImageModal(imageUrl, title, description, index) {
        // Set current index for navigation
        currentImageIndex = index;

        // Update modal content
        document.getElementById('modalImg').src = imageUrl;
        document.getElementById('modalTitle').textContent = title;
        document.getElementById('modalDescription').textContent = description;

        // Show modal
        document.getElementById('imageModal').classList.remove('hidden');
        document.getElementById('imageModal').classList.add('flex');
        document.body.style.overflow = 'hidden'; // Prevent scrolling

        console.log("Modal opened with image:", imageUrl);
    }

    function closeImageModal() {
        // Hide modal
        document.getElementById('imageModal').classList.add('hidden');
        document.getElementById('imageModal').classList.remove('flex');
        document.body.style.overflow = 'auto'; // Allow scrolling again
    }

    function nextImage() {
        event.stopPropagation();
        currentImageIndex = (currentImageIndex + 1) % allImages.length;
        updateModalImage();
    }

    function prevImage() {
        event.stopPropagation();
        currentImageIndex = (currentImageIndex - 1 + allImages.length) % allImages.length;
        updateModalImage();
    }

    function updateModalImage() {
        const image = allImages[currentImageIndex];
        document.getElementById('modalImg').src = image.image_url;
        document.getElementById('modalTitle').textContent = image.title || 'Gallery image';
        document.getElementById('modalDescription').textContent = image.alt_text || '';
    }

    // Keyboard navigation
    document.addEventListener('keydown', function(event) {
        // Only respond to keyboard if modal is open
        if (document.getElementById('imageModal').classList.contains('flex')) {
            if (event.key === 'Escape') {
                closeImageModal();
            } else if (event.key === 'ArrowRight') {
                nextImage();
            } else if (event.key === 'ArrowLeft') {
                prevImage();
            }
        }
    });
</script>
@endpush

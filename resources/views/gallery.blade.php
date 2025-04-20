<!-- resources/views/gallery.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Browse photos from His Kingdom Church events, services, and community activities.">
    <title>Gallery - His Kingdom Church</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .text-shadow {
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
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
                    },
                    spacing: {
                        '128': '32rem',
                    },
                    boxShadow: {
                        'custom': '0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
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
    <div class="relative pt-16 pb-32 flex content-center items-center justify-center min-h-[40vh]">
        <div class="absolute top-0 w-full h-full bg-center bg-cover bg-fixed" style="background-image: url('/images/gallery-banner.jpg');">
            <span class="w-full h-full absolute opacity-80 bg-gradient-to-r from-[#011EB7]/90 to-[#011EB7]/70"></span>
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
            <!-- Gallery Section -->
            <div class="mb-16">
                <div class="flex flex-col md:flex-row items-center justify-between mb-10">
                    <div class="flex items-center mb-4 md:mb-0">
                        <div class="w-20 h-1 bg-[#011EB7] hidden md:block"></div>
                        <h2 class="text-3xl md:text-4xl font-bold text-[#011EB7] md:ml-4">Photo Gallery</h2>
                    </div>
                    @auth
                        @if(auth()->user()->can('upload_images'))
                        <a href="{{ route('gallery.upload') }}"
                           class="inline-flex items-center px-6 py-2 bg-[#011EB7] text-white rounded-lg hover:bg-[#011EB7]/90
                                  transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-lg">
                            <i class="fas fa-plus-circle mr-2"></i>
                            Upload New Images
                        </a>
                        @endif
                    @endauth
                </div>

                <div class="bg-white shadow-custom rounded-lg overflow-hidden p-6">
                    <!-- Gallery Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach($images as $index => $image)
                            <div class="relative group cursor-pointer overflow-hidden rounded-lg shadow-custom h-64 transform transition-transform duration-300 hover:-translate-y-1 hover:shadow-lg">
                                <!-- Use data attributes to store image info for the modal -->
                                <img
                                    src="{{ $image['image_url'] }}"
                                    alt="{{ $image['alt_text'] ?? 'Gallery image' }}"
                                    class="w-full h-64 object-cover transition-transform duration-300 group-hover:scale-110"
                                    onclick="openImageModal('{{ $image['image_url'] }}', '{{ $image['title'] ?? 'Gallery image' }}', '{{ $image['alt_text'] ?? '' }}', {{ $index }})"
                                >
                                <div class="absolute inset-0 bg-[#011EB7] opacity-0 group-hover:opacity-20 transition-opacity duration-300"></div>
                                <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-[#011EB7]/80 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <h3 class="text-white font-semibold">{{ $image['title'] ?? 'Gallery image' }}</h3>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if(count($images) == 0)
                        <div class="flex flex-col items-center justify-center py-12 text-center">
                            <div class="w-20 h-20 bg-[#011EB7]/10 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-images text-[#011EB7] text-3xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-700 mb-2">No Images Available</h3>
                            <p class="text-gray-500">Our gallery will be updated with new images soon.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Categories Section -->
            <div class="bg-gray-50 rounded-lg shadow-custom p-8 mb-16">
                <h3 class="text-2xl font-bold text-[#011EB7] mb-6 flex items-center">
                    <i class="fas fa-layer-group text-[#E0B041] mr-3"></i>
                    Photo Categories
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <a href="#" class="bg-white p-4 rounded-lg shadow-sm hover:shadow-lg transition-all flex flex-col items-center justify-center text-center">
                        <div class="w-12 h-12 bg-[#011EB7]/10 rounded-full flex items-center justify-center mb-3">
                            <i class="fas fa-church text-[#011EB7]"></i>
                        </div>
                        <span class="font-semibold text-gray-800">Church Services</span>
                    </a>
                    <a href="#" class="bg-white p-4 rounded-lg shadow-sm hover:shadow-lg transition-all flex flex-col items-center justify-center text-center">
                        <div class="w-12 h-12 bg-[#011EB7]/10 rounded-full flex items-center justify-center mb-3">
                            <i class="fas fa-hands-helping text-[#011EB7]"></i>
                        </div>
                        <span class="font-semibold text-gray-800">Outreach</span>
                    </a>
                    <a href="#" class="bg-white p-4 rounded-lg shadow-sm hover:shadow-lg transition-all flex flex-col items-center justify-center text-center">
                        <div class="w-12 h-12 bg-[#011EB7]/10 rounded-full flex items-center justify-center mb-3">
                            <i class="fas fa-music text-[#011EB7]"></i>
                        </div>
                        <span class="font-semibold text-gray-800">Worship</span>
                    </a>
                    <a href="#" class="bg-white p-4 rounded-lg shadow-sm hover:shadow-lg transition-all flex flex-col items-center justify-center text-center">
                        <div class="w-12 h-12 bg-[#011EB7]/10 rounded-full flex items-center justify-center mb-3">
                            <i class="fas fa-users text-[#011EB7]"></i>
                        </div>
                        <span class="font-semibold text-gray-800">Community</span>
                    </a>
                </div>
            </div>

            <!-- CTA Section -->
            <div class="text-center py-12 px-4 sm:px-6 lg:py-16 lg:px-8 bg-gradient-to-r from-[#011EB7] to-[#011EB7]/90 text-white rounded-xl shadow-custom overflow-hidden relative mb-16">
                <div class="absolute top-0 right-0 opacity-10 pointer-events-none">
                    <i class="fas fa-camera text-9xl"></i>
                </div>
                <div class="relative z-10">
                    <h2 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl mb-4">
                        <span class="block">Have photos to share?</span>
                    </h2>
                    <p class="mt-4 text-lg leading-6 text-white/90 max-w-3xl mx-auto">
                        If you have captured special moments at His Kingdom Church events, services, or activities,
                        we'd love to see them! Share your photos with our community.
                    </p>
                    <div class="mt-8 flex justify-center">
                        <div class="inline-flex rounded-md shadow">
                            <a href="/contact" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-[#011EB7] bg-[#E0B041] hover:bg-white transition-colors">
                                Share Your Photos
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Simple Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-90 hidden z-50" onclick="closeImageModal()">
        <div class="absolute top-5 right-5">
            <button onclick="closeImageModal()" class="text-white hover:text-gray-300 focus:outline-none">
                <i class="fas fa-times text-3xl"></i>
            </button>
        </div>

        <div class="absolute top-1/2 left-5 transform -translate-y-1/2">
            <button onclick="prevImage()" class="text-white bg-[#011EB7]/50 p-3 rounded-full hover:bg-[#011EB7]/70 focus:outline-none">
                <i class="fas fa-chevron-left text-xl"></i>
            </button>
        </div>

        <div class="absolute top-1/2 right-5 transform -translate-y-1/2">
            <button onclick="nextImage()" class="text-white bg-[#011EB7]/50 p-3 rounded-full hover:bg-[#011EB7]/70 focus:outline-none">
                <i class="fas fa-chevron-right text-xl"></i>
            </button>
        </div>

        <div class="flex items-center justify-center h-full" onclick="event.stopPropagation()">
            <div class="max-w-4xl mx-auto p-4">
                <img id="modalImg" src="" alt="Enlarged view" class="max-h-[80vh] max-w-full rounded-lg shadow-2xl">
                <div class="text-white text-center mt-4">
                    <h3 id="modalTitle" class="text-xl font-bold"></h3>
                    <p id="modalDescription" class="text-gray-300 mt-2"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Scroll to Top Button -->
    <button id="scrollToTop"
            class="fixed bottom-8 right-8 bg-[#011EB7] text-white p-3 rounded-full shadow-lg hover:bg-[#011EB7]/90 transition-all hidden z-40">
        <i class="fas fa-arrow-up text-lg"></i>
    </button>

    <!-- Footer -->
    @include('layouts.footer')

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

        // Scroll to Top Button
        const scrollToTop = document.getElementById('scrollToTop');

        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                scrollToTop.classList.remove('hidden');
            } else {
                scrollToTop.classList.add('hidden');
            }
        });

        scrollToTop.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    </script>
</body>
</html>

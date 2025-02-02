@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Gallery Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($images as $image)
                <div class="relative group cursor-pointer overflow-hidden rounded-lg shadow-lg h-64">
                    <img src="{{ Storage::url($image->image_path) }}"
                         alt="{{ $image->alt_text }}"
                         class="w-full h-64 object-cover transition-transform duration-300 group-hover:scale-110"
                         onclick="openModal('{{ $image->image_url }}', '{{ $image->title }}')">
                    <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-25 transition-opacity duration-300"></div>
                </div>
            @endforeach
        </div>

        <!-- Modal -->
        <div id="imageModal"
             class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-4 hidden"
             onclick="closeModal()">
            <div class="relative max-w-4xl w-full">
                <button onclick="closeModal()"
                        class="absolute -top-10 right-0 text-white hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                </button>
                <img id="modalImage"
                     src=""
                     alt="Enlarged view"
                     class="w-full h-auto rounded-lg">
                <div id="modalTitle" class="text-white text-center mt-4 text-lg"></div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function openModal(imagePath, title) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            const modalTitle = document.getElementById('modalTitle');
            modalImage.src = imagePath;
            modalTitle.textContent = title;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close modal with escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal();
            }
        });
    </script>
@endpush

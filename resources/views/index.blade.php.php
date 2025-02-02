@extends('layouts.app')

@section('content')
    <div id="gallery-root"></div>
@endsection

@push('scripts')
    @vite(['resources/js/app.js'])
    <script>
        // Make the images data available to React
        window.galleryImages = @json($images);
    </script>
@endpush

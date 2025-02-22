<?php

namespace App\Http\Controllers;

use App\Models\GalleryImage;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index()
    {
        $images = GalleryImage::where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(function($image) {
                return [
                    'image_path' => $image->image_url,
                    'title' => $image->title ?? 'Gallery Image',
                    'alt_text' => $image->alt_text ?? 'Gallery image description',
                    'id' => $image->id
                ];
            });

        // Debug information
        \Log::info('Processed images for gallery:', $images->toArray());

        return view('gallery.index', compact('images'));
        // Make sure this matches the path: resources/views/gallery/index.blade.php
    }
}

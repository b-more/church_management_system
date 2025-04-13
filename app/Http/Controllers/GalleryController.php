<?php

namespace App\Http\Controllers;

use App\Models\GalleryImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class GalleryController extends Controller
{
    public function index()
    {
        // Get active images with proper URLs
        $images = GalleryImage::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // Log raw data from database
        Log::info('Raw gallery images from database:', [
            'count' => $images->count(),
            'first_image' => $images->first() ? $images->first()->toArray() : null
        ]);

        // Transform images with explicit URLs
        $processedImages = $images->map(function($image) {
            // Generate both relative and absolute URLs for debugging
            $storagePath = $image->image_path;
            $storageUrl = Storage::disk('public')->url($storagePath);
            $absoluteUrl = url($storageUrl);

            return [
                'id' => $image->id,
                'title' => $image->title ?? 'Gallery Image',
                'alt_text' => $image->alt_text ?? 'Gallery image description',
                'image_path' => $storagePath, // Original path stored in database
                'image_url' => $storageUrl,   // URL with /storage/ prefix
                'absolute_url' => $absoluteUrl, // Full URL with domain
                'created_at' => $image->created_at ? $image->created_at->format('Y-m-d H:i:s') : null,
            ];
        });

        // Debug all image URLs
        if ($processedImages->count() > 0) {
            Log::info('Image URL examples:', [
                'First image paths' => [
                    'image_path' => $processedImages->first()['image_path'],
                    'image_url' => $processedImages->first()['image_url'],
                    'absolute_url' => $processedImages->first()['absolute_url'],
                ]
            ]);
        }

        // Confirm storage configuration without accessing adapter directly
        Log::info('Storage configuration:', [
            'storage_link_exists' => file_exists(public_path('storage')),
            'public_url_base' => Storage::disk('public')->url(''),
        ]);

        return view('gallery.index', ['images' => $processedImages]);
    }

    // Add the upload form and upload handling methods if needed
    public function showUploadForm()
    {
        // Check if user has permission to upload
        $this->authorize('upload_images');

        return view('gallery.upload');
    }

    public function upload(Request $request)
    {
        // Check if user has permission to upload
        $this->authorize('upload_images');

        // Validate the request
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'alt_text' => 'nullable|string|max:255',
            'image' => 'required|image|max:2048', // 2MB max
        ]);

        // Store the image
        $imagePath = $request->file('image')->store('gallery/images', 'public');
        Log::info('New image uploaded:', [
            'path' => $imagePath,
            'full_url' => Storage::disk('public')->url($imagePath)
        ]);

        // Create the gallery image record
        $image = GalleryImage::create([
            'title' => $validated['title'],
            'alt_text' => $validated['alt_text'] ?? null,
            'image_path' => $imagePath,
            'is_active' => true,
            'sort_order' => GalleryImage::max('sort_order') + 1, // Put at the end
        ]);

        return redirect()->route('gallery.index')
            ->with('success', 'Image uploaded successfully!');
    }
}

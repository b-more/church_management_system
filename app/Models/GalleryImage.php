<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage; // Add this correct import

class GalleryImage extends Model
{
    protected $fillable = [
        'title',
        'alt_text',
        'image_path',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getImageUrlAttribute()
    {
        return Storage::disk('public')->url($this->image_path);
    }
}

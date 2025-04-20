<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Notice extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'recipient_group',
        'title',
        'body',
        'image_path',
        'is_active',
        'view_count'
    ];

    protected $casts = [
        'date' => 'date',
        'is_active' => 'boolean',
    ];

    public function incrementViewCount()
    {
        $this->increment('view_count');
    }

    public function getExcerpt($length = 100)
    {
        return Str::limit(strip_tags($this->body), $length);
    }

    public function getShareableLink()
    {
        return route('notices.show', $this->id);
    }
}

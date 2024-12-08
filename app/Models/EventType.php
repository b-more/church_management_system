<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description',
        'category',
        'registration_required',
        'is_active',
        'notes'
    ];

    protected $casts = [
        'registration_required' => 'boolean',
        'is_active' => 'boolean'
    ];

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
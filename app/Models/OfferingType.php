<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfferingType extends Model
{

    protected $fillable = [
        'name',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];
}

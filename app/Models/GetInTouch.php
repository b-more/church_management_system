<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GetInTouch extends Model
{
    use HasFactory, SoftDeletes;

    // Updated to match migration table name
    protected $table = 'get_in_touches';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'message',
        'is_read'
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];
}

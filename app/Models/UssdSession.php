<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UssdSession extends Model
{
    protected $fillable = [
        'session_id',
        'phone_number',
        'case_no',
        'step_no',
        'data'
    ];

    protected $casts = [
        'data' => 'array'
    ];
}

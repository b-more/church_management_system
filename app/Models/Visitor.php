<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'church_id', 
        'name', 
        'phone_number', 
        'email', 
        'visit_date'
    ];
}

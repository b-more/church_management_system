<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventRegistration extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'event_id',
        'member_id',
        'registration_number',
        'status',
        'attendance_status',
        'registered_at',
        'confirmed_at',
        'attended_at',
        'payment_status',
        'amount_paid',
        'special_requirements',
        'notes'
    ];

    protected $casts = [
        'registered_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'attended_at' => 'datetime',
        'amount_paid' => 'decimal:2'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
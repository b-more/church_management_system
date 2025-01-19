<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UssdPrayerRequest extends Model
{
    protected $fillable = [
        'phone_number',
        'member_id',
        'prayer_request',
        'status', // pending, prayed, completed
        'notes'
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}

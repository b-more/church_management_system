<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UssdGiving extends Model
{
    protected $fillable = [
        'phone_number',
        'member_id',
        'amount',
        'giving_type', // tithe, offering, special_offering
        'payment_reference',
        'status', // pending, completed, failed
        'notes'
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}

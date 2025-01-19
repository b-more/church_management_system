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
        'full_name',
        'status', // pending, completed, failed
        'notes',
        'offering_type_id',
        'ussd_session_id'
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}

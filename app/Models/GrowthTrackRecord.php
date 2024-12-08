<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrowthTrackRecord extends Model
{
    protected $fillable = [
        'member_id',
        'track_type',          // Membership, Foundation, Leadership
        'start_date',
        'completion_date',
        'instructor_id',
        'status',             // Not Started, In Progress, Completed
        'score',
        'notes',
        'certificate_issued',
        'certificate_number'
    ];

    protected $casts = [
        'start_date' => 'date',
        'completion_date' => 'date',
        'certificate_issued' => 'boolean',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function instructor()
    {
        return $this->belongsTo(Member::class, 'instructor_id');
    }
}


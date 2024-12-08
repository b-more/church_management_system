<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'branch_id',
        'department_id',
        'title',
        'description',
        'event_type',        // Seminar, Conference, Workshop, etc.
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'venue',
        'venue_address',
        'organizer_id',      // Member responsible
        'coordinator_id',    // Member coordinating
        'budget',
        'expected_attendance',
        'actual_attendance',
        'registration_required',
        'registration_deadline',
        'status',            // Planned, Ongoing, Completed, Cancelled
        'notes'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'registration_deadline' => 'datetime',
        'registration_required' => 'boolean',
        'budget' => 'decimal:2'
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function organizer()
    {
        return $this->belongsTo(Member::class, 'organizer_id');
    }

    public function coordinator()
    {
        return $this->belongsTo(Member::class, 'coordinator_id');
    }
}

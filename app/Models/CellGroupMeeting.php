<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CellGroupMeeting extends Model
{
    protected $fillable = [
        'cell_group_id',
        'date',
        'start_time',
        'end_time',
        'meeting_type',           // Regular, Special, Combined, Fellowship
        'venue',                  // If different from regular cell group venue
        'venue_address',
        'host_id',               // Member hosting the meeting
        'leader_id',             // Who led the meeting
        'topic',
        'bible_reading',
        'total_attendance',
        'total_members_present',
        'total_visitors',
        'offering_amount',
        'status',                // Scheduled, Completed, Cancelled
        'cancellation_reason',
        'next_meeting_date',
        'testimonies',           // JSON field for testimonies shared
        'prayer_points',         // JSON field for prayer points
        'announcements',         // JSON field for announcements
        'notes'
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'next_meeting_date' => 'date',
        'testimonies' => 'array',
        'prayer_points' => 'array',
        'announcements' => 'array',
        'offering_amount' => 'decimal:2'
    ];

    // Relationships
    public function cellGroup()
    {
        return $this->belongsTo(CellGroup::class);
    }

    public function host()
    {
        return $this->belongsTo(Member::class, 'host_id');
    }

    public function leader()
    {
        return $this->belongsTo(Member::class, 'leader_id');
    }

    public function attendees()
    {
        return $this->hasMany(CellGroupAttendance::class);
    }

    public function presentMembers()
    {
        return $this->belongsToMany(Member::class, 'cell_group_attendance')
            ->withPivot(['attendance_type', 'arrival_time', 'notes']);
    }
}

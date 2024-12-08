<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CellGroupAttendance extends Model
{
    protected $fillable = [
        'cell_group_meeting_id',
        'member_id',
        'attendance_type',        // Member, Visitor, First Timer
        'arrival_time',
        'visitor_name',           // For non-member attendees
        'visitor_phone',
        'visitor_address',
        'follow_up_required',
        'follow_up_notes',
        'notes'
    ];

    protected $casts = [
        'arrival_time' => 'datetime',
        'follow_up_required' => 'boolean'
    ];

    public function meeting()
    {
        return $this->belongsTo(CellGroupMeeting::class, 'cell_group_meeting_id');
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}


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
        'image_path',
        'event_type',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'venue',
        'venue_address',
        'organizer_id',
        'coordinator_id',
        'budget',
        'expected_attendance',
        'actual_attendance',
        'registration_required',
        'registration_deadline',
        'status',
        'notes',
        'created_by_id'
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

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function getFormattedDateTimeAttribute()
    {
        $startDate = $this->start_date->format('F j, Y');
        $endDate = $this->end_date->format('F j, Y');

        if($startDate === $endDate) {
            return $startDate . ' • ' .
                  date('g:i A', strtotime($this->start_time)) . ' - ' .
                  date('g:i A', strtotime($this->end_time));
        }

        return $startDate . ' - ' . $endDate . ' • ' .
               date('g:i A', strtotime($this->start_time)) . ' - ' .
               date('g:i A', strtotime($this->end_time));
    }

    public function getRegistrationCountAttribute()
    {
        return $this->registrations()->count();
    }

    public function getAttendanceCountAttribute()
    {
        return $this->registrations()->where('attendance_status', 'Present')->count();
    }

    public function getRegistrationPercentageAttribute()
    {
        if(!$this->expected_attendance) return 0;

        return min(100, round(($this->registration_count / $this->expected_attendance) * 100));
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'branch_id',
        'service_type',
        'service_name',
        'date',
        'start_time',
        'end_time',
        'host_id',
        'intercession_leader_id',
        'offering_exhortation_leader_id',
        'sunday_school_teacher_id',
        'preacher_type',
        'preacher_id',
        'visiting_preacher_name',
        'visiting_preacher_church',
        'visiting_preacher_city',
        'visiting_preacher_country',
        'visiting_preacher_phone',
        'worship_leader_id',
        'announcer_id',
        'message_title',
        'bible_reading',
        'service_banner',
        'audio_recording',
        'facebook_stream_link',
        'youtube_stream_link',
        'total_attendance',
        'total_first_timers',
        'total_members',
        'total_visitors',
        'total_children',
        'offering_amount',
        'tithe_amount',
        'status',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'offering_amount' => 'decimal:2',
        'total_attendance' => 'integer',
        'total_first_timers' => 'integer',
        'total_members' => 'integer',
        'total_visitors' => 'integer',
        'offering_amount' => 'decimal:2',
        'tithe_amount' => 'decimal:2',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function host(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'host_id');
    }

    public function preacher(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'preacher_id');
    }

    public function intercessionLeader(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'intercession_leader_id');
    }

    public function offeringExhortationLeader(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'offering_exhortation_leader_id');
    }

    public function sundaySchoolTeacher(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'sunday_school_teacher_id');
    }

    public function worshipLeader(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'worship_leader_id');
    }

    public function attendance(): HasMany
    {
        return $this->hasMany(ServiceAttendance::class);
    }

    public function offerings(): HasMany
    {
        return $this->hasMany(Offering::class);
    }

    public function announcer(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'announcer_id');
    }

    // Helper method to get preacher display name
    public function getPreacherDisplayNameAttribute(): string
    {
        if ($this->preacher_type === 'visiting') {
            return $this->visiting_preacher_name . ' (' . $this->visiting_preacher_church . ')';
        }
        return $this->preacher?->full_name ?? 'Not Assigned';
    }
}
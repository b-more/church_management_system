<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DutyRoster extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'service_type',
        'service_date',
        'service_time',
        'end_time', // Added
        'service_host_id',
        'intercession_leader_id',
        'worship_leader_id',
        'announcer_id',
        'exhortation_leader_id',
        'sunday_school_teacher_id',
        'special_song_group', // Changed from special_song_singer_id
        'preacher_type',
        'preacher_id',
        'visiting_preacher_name',
        'visiting_preacher_church',
        'notes',
        'status',
    ];

    protected $casts = [
        'service_date' => 'date',
        'service_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    // Relationships
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function serviceHost(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'service_host_id');
    }

    public function intercessionLeader(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'intercession_leader_id');
    }

    public function worshipLeader(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'worship_leader_id');
    }

    public function announcer(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'announcer_id');
    }

    public function exhortationLeader(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'exhortation_leader_id');
    }

    public function sundaySchoolTeacher(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'sunday_school_teacher_id');
    }

    public function preacher(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'preacher_id');
    }

    // Accessors
    public function getFormattedServiceTimeAttribute()
    {
        return $this->service_time ? $this->service_time->format('H:i') : null;
    }

    public function getFormattedEndTimeAttribute()
    {
        return $this->end_time ? $this->end_time->format('H:i') : null;
    }

    public function getServiceDurationAttribute()
    {
        if ($this->service_time && $this->end_time) {
            return $this->service_time->diffInMinutes($this->end_time) . ' minutes';
        }
        return null;
    }

    public function getPreacherNameAttribute()
    {
        if ($this->preacher_type === 'visiting') {
            return $this->visiting_preacher_name;
        }

        return $this->preacher ?
            "{$this->preacher->title} {$this->preacher->first_name} {$this->preacher->last_name}" :
            null;
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('service_date', '>=', now()->toDateString());
    }

    public function scopePast($query)
    {
        return $query->where('service_date', '<', now()->toDateString());
    }
}

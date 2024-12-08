<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DutyRoster extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'branch_id',
        'service_date',
        'service_type',
        'service_time',
        'service_host_id',
        'intercession_leader_id',
        'worship_leader_id',
        'announcer_id',
        'exhortation_leader_id',
        'sunday_school_teacher_id',
        'special_song_singer_id',
        'preacher_type',
        'preacher_id',
        'visiting_preacher_name',
        'visiting_preacher_church',
        'notes',
        'status'
    ];

    protected $casts = [
        'service_date' => 'date',
        'service_time' => 'datetime:H:i',
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

    public function specialSongSinger(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'special_song_singer_id');
    }

    public function preacher(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'preacher_id');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CellGroup extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'branch_id',
        'leader_id',
        'meeting_day',
        'meeting_time',
        'meeting_location',
        'description',
        'status'
    ];

    protected $casts = [
        'meeting_time' => 'datetime:H:i', // This will properly cast meeting_time
        'status' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function leader(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'leader_id');
    }

    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    // Accessors
    public function getMeetingTimeFormattedAttribute(): string
    {
        if (!$this->meeting_time) {
            return 'Not Set';
        }

        try {
            return $this->meeting_time->format('H:i');
        } catch (\Exception $e) {
            return (string) $this->meeting_time;
        }
    }
}

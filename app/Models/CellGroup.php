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
}
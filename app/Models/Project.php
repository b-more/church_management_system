<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'project_category_id',
        'name',
        'description',
        'target_amount',
        'current_amount',
        'start_date',
        'end_date',
        'status',
        'notes'
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'current_amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date'
    ];

    // Relationships
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function projectCategory()
    {
        return $this->belongsTo(ProjectCategory::class);
    }

    public function pledges()
    {
        return $this->hasMany(Pledge::class);
    }

    public function incomes()
    {
        return $this->hasMany(Income::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeByBranch($query, $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('project_category_id', $categoryId);
    }

    // Accessors
    public function getProgressPercentageAttribute()
    {
        if ($this->target_amount == 0) return 0;
        return min(100, round(($this->current_amount / $this->target_amount) * 100, 2));
    }

    public function getRemainingAmountAttribute()
    {
        return max(0, $this->target_amount - $this->current_amount);
    }

    public function getIsOverdueAttribute()
    {
        if (!$this->end_date) return false;
        return $this->end_date->isPast() && $this->status !== 'completed';
    }

    public function getTotalPledgedAttribute()
    {
        return $this->pledges()->sum('total_amount');
    }

    public function getTotalReceivedAttribute()
    {
        return $this->incomes()->sum('amount');
    }

    // Methods
    public function updateCurrentAmount()
    {
        $this->current_amount = $this->incomes()->sum('amount');
        $this->save();

        // Auto-complete if target reached
        if ($this->current_amount >= $this->target_amount && $this->status === 'active') {
            $this->status = 'completed';
            $this->save();
        }
    }
}

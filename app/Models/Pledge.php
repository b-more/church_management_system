<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Pledge extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'member_id',
        'project_id',
        'name',
        'phone_number',
        'total_amount',
        'frequency',
        'frequency_amount',
        'received_amount',
        'pledge_date',
        'target_completion_date',
        'status',
        'notes'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'frequency_amount' => 'decimal:2',
        'received_amount' => 'decimal:2',
        'pledge_date' => 'date',
        'target_completion_date' => 'date'
    ];

    // Relationships
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
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

    public function scopeDefaulted($query)
    {
        return $query->where('status', 'defaulted');
    }

    public function scopeByBranch($query, $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    public function scopeByProject($query, $projectId)
    {
        return $query->where('project_id', $projectId);
    }

    public function scopeOverdue($query)
    {
        return $query->where('target_completion_date', '<', now())
                    ->where('status', 'active')
                    ->whereColumn('received_amount', '<', 'total_amount');
    }

    // Accessors
    public function getPledgerNameAttribute()
    {
        return $this->member ? $this->member->full_name : $this->name;
    }

    public function getPledgerPhoneAttribute()
    {
        return $this->member ? $this->member->phone : $this->phone_number;
    }

    public function getRemainingAmountAttribute()
    {
        return max(0, $this->total_amount - $this->received_amount);
    }

    public function getCompletionPercentageAttribute()
    {
        if ($this->total_amount == 0) return 0;
        return min(100, round(($this->received_amount / $this->total_amount) * 100, 2));
    }

    public function getIsOverdueAttribute()
    {
        if (!$this->target_completion_date) return false;
        return $this->target_completion_date->isPast() && $this->remaining_amount > 0 && $this->status === 'active';
    }

    public function getLastPaymentDateAttribute()
    {
        return $this->incomes()->latest('date')->value('date');
    }

    public function getExpectedPaymentsCountAttribute()
    {
        if ($this->frequency === 'one-time') return 1;
        if (!$this->frequency_amount || $this->frequency_amount == 0) return 0;

        return ceil($this->total_amount / $this->frequency_amount);
    }

    public function getActualPaymentsCountAttribute()
    {
        return $this->incomes()->count();
    }

    public function getNextPaymentDueDateAttribute()
    {
        if ($this->frequency === 'one-time') return null;
        if ($this->status !== 'active') return null;

        $lastPayment = $this->last_payment_date;
        $baseDate = $lastPayment ? Carbon::parse($lastPayment) : $this->pledge_date;

        return match($this->frequency) {
            'weekly' => $baseDate->addWeek(),
            'bi-weekly' => $baseDate->addWeeks(2),
            'monthly' => $baseDate->addMonth(),
            'quarterly' => $baseDate->addMonths(3),
            'yearly' => $baseDate->addYear(),
            default => null
        };
    }

    // Methods
    public function updateReceivedAmount()
    {
        $this->received_amount = $this->incomes()->sum('amount');

        // Auto-complete if total amount reached
        if ($this->received_amount >= $this->total_amount && $this->status === 'active') {
            $this->status = 'completed';
        }

        $this->save();

        // Update project current amount if linked
        if ($this->project) {
            $this->project->updateCurrentAmount();
        }
    }

    public function markAsDefaulted($reason = null)
    {
        $this->status = 'defaulted';
        if ($reason) {
            $this->notes = ($this->notes ? $this->notes . "\n" : '') . "Defaulted: " . $reason;
        }
        $this->save();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Income extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'offering_type_id',
        'member_id',
        'project_id',
        'pledge_id',
        'partnership_id',
        'name',
        'phone_number',
        'amount',
        'date',
        'week_number',
        'month',
        'year',
        'narration',
        'payment_method',
        'reference_number',
        'recorded_by',
        'recorded_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
        'recorded_at' => 'datetime'
    ];

    // Boot method to auto-calculate date fields
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($income) {
            $date = Carbon::parse($income->date);
            $income->week_number = $date->weekOfYear;
            $income->month = $date->month;
            $income->year = $date->year;
            $income->recorded_by = auth()->id();
            $income->recorded_at = now();
        });

        static::updating(function ($income) {
            if ($income->isDirty('date')) {
                $date = Carbon::parse($income->date);
                $income->week_number = $date->weekOfYear;
                $income->month = $date->month;
                $income->year = $date->year;
            }
        });

        static::created(function ($income) {
            // Update related models
            if ($income->pledge_id) {
                $income->pledge->updateReceivedAmount();
            }
            if ($income->project_id) {
                $income->project->updateCurrentAmount();
            }
        });

        static::updated(function ($income) {
            // Update related models if amount changed
            if ($income->isDirty('amount')) {
                if ($income->pledge_id) {
                    $income->pledge->updateReceivedAmount();
                }
                if ($income->project_id) {
                    $income->project->updateCurrentAmount();
                }
            }
        });

        static::deleted(function ($income) {
            // Update related models
            if ($income->pledge_id) {
                $income->pledge->updateReceivedAmount();
            }
            if ($income->project_id) {
                $income->project->updateCurrentAmount();
            }
        });
    }

    // Relationships
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function offeringType()
    {
        return $this->belongsTo(OfferingType::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function pledge()
    {
        return $this->belongsTo(Pledge::class);
    }

    public function partnership()
    {
        return $this->belongsTo(Partnership::class);
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    // Scopes
    public function scopeByBranch($query, $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    public function scopeByOfferingType($query, $typeId)
    {
        return $query->where('offering_type_id', $typeId);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    public function scopeByMonth($query, $month, $year = null)
    {
        $query->where('month', $month);
        if ($year) {
            $query->where('year', $year);
        }
        return $query;
    }

    public function scopeByYear($query, $year)
    {
        return $query->where('year', $year);
    }

    public function scopeByWeek($query, $week, $year = null)
    {
        $query->where('week_number', $week);
        if ($year) {
            $query->where('year', $year);
        }
        return $query;
    }

    public function scopeTithe($query)
    {
        return $query->whereHas('offeringType', function ($q) {
            $q->where('name', 'Tithe');
        });
    }

    public function scopeOffering($query)
    {
        return $query->whereHas('offeringType', function ($q) {
            $q->where('name', 'Offering');
        });
    }

    public function scopeProjects($query)
    {
        return $query->whereHas('offeringType', function ($q) {
            $q->where('name', 'Projects');
        });
    }

    public function scopePartnerships($query)
    {
        return $query->whereHas('offeringType', function ($q) {
            $q->where('name', 'Financial Partnership');
        });
    }

    // Accessors
    public function getContributorNameAttribute()
    {
        if ($this->member) {
            return $this->member->full_name;
        }
        return $this->name ?: 'Anonymous';
    }

    public function getContributorPhoneAttribute()
    {
        if ($this->member) {
            return $this->member->phone;
        }
        return $this->phone_number;
    }

    public function getFormattedAmountAttribute()
    {
        return 'K' . number_format($this->amount, 2);
    }

    public function getIsAnonymousAttribute()
    {
        return !$this->member_id && !$this->name;
    }

    // Static methods for quick summaries
    public static function getTotalByBranch($branchId, $startDate = null, $endDate = null)
    {
        $query = self::where('branch_id', $branchId);

        if ($startDate && $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        }

        return $query->sum('amount');
    }

    public static function getTotalByOfferingType($offeringTypeId, $branchId = null, $startDate = null, $endDate = null)
    {
        $query = self::where('offering_type_id', $offeringTypeId);

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        if ($startDate && $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        }

        return $query->sum('amount');
    }

    public static function getMonthlyTotals($year, $branchId = null)
    {
        $query = self::where('year', $year);

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        return $query->selectRaw('month, SUM(amount) as total')
                    ->groupBy('month')
                    ->orderBy('month')
                    ->get()
                    ->pluck('total', 'month')
                    ->toArray();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Partnership extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'member_id',
        'name',
        'phone_number',
        'monthly_amount',
        'agreement_file',
        'start_date',
        'end_date',
        'status',
        'notes'
    ];

    protected $casts = [
        'monthly_amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date'
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

    public function incomes()
    {
        return $this->hasMany(Income::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function scopeByBranch($query, $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    // Accessors
    public function getContributorNameAttribute()
    {
        return $this->member ? $this->member->full_name : $this->name;
    }

    public function getContributorPhoneAttribute()
    {
        return $this->member ? $this->member->phone : $this->phone_number;
    }

    public function getTotalContributedAttribute()
    {
        return $this->incomes()->sum('amount');
    }

    public function getLastContributionDateAttribute()
    {
        return $this->incomes()->latest('date')->value('date');
    }

    public function getMonthsActiveAttribute()
    {
        $start = $this->start_date;
        $end = $this->end_date ?: now();
        return $start->diffInMonths($end) + 1;
    }

    public function getExpectedTotalAttribute()
    {
        return $this->monthly_amount * $this->months_active;
    }

    public function getContributionVarianceAttribute()
    {
        return $this->total_contributed - $this->expected_total;
    }

    public function getAgreementFileUrlAttribute()
    {
        if (!$this->agreement_file) return null;
        return Storage::url($this->agreement_file);
    }

    // Methods
    public function uploadAgreementFile($file)
    {
        // Delete old file if exists
        if ($this->agreement_file) {
            Storage::delete($this->agreement_file);
        }

        // Store new file
        $fileName = 'partnership_' . $this->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('partnerships', $fileName);

        $this->agreement_file = $path;
        $this->save();

        return $path;
    }

    public function deleteAgreementFile()
    {
        if ($this->agreement_file) {
            Storage::delete($this->agreement_file);
            $this->agreement_file = null;
            $this->save();
        }
    }
}

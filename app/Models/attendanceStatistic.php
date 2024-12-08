<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class AttendanceStatistic extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'member_id',
        'branch_id',
        'year',
        'month',
        'total_services',
        'attended_services',
        'attendance_percentage',
        'last_attendance_date',
        'consecutive_absences',
        'notes'
    ];

    protected $casts = [
        'last_attendance_date' => 'date',
        'attendance_percentage' => 'decimal:2',
        'year' => 'integer',
        'month' => 'integer',
        'total_services' => 'integer',
        'attended_services' => 'integer',
        'consecutive_absences' => 'integer'
    ];

    // Relationships
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    // Scopes
    public function scopeForYear(Builder $query, int $year): Builder
    {
        return $query->where('year', $year);
    }

    public function scopeForMonth(Builder $query, int $month): Builder
    {
        return $query->where('month', $month);
    }

    public function scopeForBranch(Builder $query, int $branchId): Builder
    {
        return $query->where('branch_id', $branchId);
    }

    public function scopeLowAttendance(Builder $query, float $threshold = 50.0): Builder
    {
        return $query->where('attendance_percentage', '<', $threshold);
    }

    public function scopeHighAttendance(Builder $query, float $threshold = 80.0): Builder
    {
        return $query->where('attendance_percentage', '>=', $threshold);
    }

    // Accessors & Mutators
    public function getMonthNameAttribute(): string
    {
        return Carbon::create(null, $this->month)->format('F');
    }

    public function getYearMonthAttribute(): string
    {
        return Carbon::create($this->year, $this->month)->format('F Y');
    }

    // Helper Methods
    public static function calculateAttendancePercentage(int $attended, int $total): float
    {
        if ($total === 0) return 0.0;
        return round(($attended / $total) * 100, 2);
    }

    public function updateAttendancePercentage(): void
    {
        $this->attendance_percentage = self::calculateAttendancePercentage(
            $this->attended_services,
            $this->total_services
        );
        $this->save();
    }

    public function incrementAttendedServices(): void
    {
        $this->attended_services++;
        $this->last_attendance_date = now();
        $this->consecutive_absences = 0;
        $this->updateAttendancePercentage();
    }

    public function incrementTotalServices(): void
    {
        $this->total_services++;
        $this->updateAttendancePercentage();
    }

    public function incrementConsecutiveAbsences(): void
    {
        $this->consecutive_absences++;
        $this->save();
    }

    // Static Methods
    public static function generateMonthlyStatistic(
        Member $member,
        Branch $branch,
        int $year,
        int $month
    ): self {
        return self::create([
            'member_id' => $member->id,
            'branch_id' => $branch->id,
            'year' => $year,
            'month' => $month,
            'total_services' => 0,
            'attended_services' => 0,
            'attendance_percentage' => 0.00,
            'consecutive_absences' => 0,
        ]);
    }

    // Validation Rules
    public static function rules(): array
    {
        return [
            'member_id' => ['required', 'exists:members,id'],
            'branch_id' => ['required', 'exists:branches,id'],
            'year' => ['required', 'integer', 'min:2000', 'max:' . (date('Y') + 1)],
            'month' => ['required', 'integer', 'min:1', 'max:12'],
            'total_services' => ['required', 'integer', 'min:0'],
            'attended_services' => ['required', 'integer', 'min:0'],
            'attendance_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
            'last_attendance_date' => ['nullable', 'date'],
            'consecutive_absences' => ['required', 'integer', 'min:0'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
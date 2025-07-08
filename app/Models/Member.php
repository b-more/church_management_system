<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Member extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        // Personal Information
        'registration_number', 'title', 'first_name', 'last_name',
        'date_of_birth', 'gender',

        // Contact Information
        'phone', 'alternative_phone', 'email', 'address',

        // Church Information
        'branch_id', 'cell_group_id', 'membership_status', 'membership_date',

        // Spiritual Information
        'salvation_date', 'baptism_date', 'baptism_type',
        'membership_class_status', 'foundation_class_status', 'leadership_class_status',

        // Additional Information
        'marital_status', 'occupation', 'employer',

        // Emergency Contact
        'emergency_contact_name', 'emergency_contact_phone',

        // Previous Church
        'previous_church', 'previous_church_pastor',

        // Skills & Interests
        'skills_talents', 'interests', 'special_needs',

        // Status
        'is_active', 'deactivation_reason', 'notes',

        // Ministry Roles
        'is_pastor', 'is_intercessor', 'is_usher', 'is_worship_leader',
        'is_sunday_school_teacher', 'is_offering_exhortation_leader',
        'is_eligible_for_pulpit_ministry'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'membership_date' => 'date',
        'salvation_date' => 'date',
        'baptism_date' => 'date',
        'is_active' => 'boolean',

        // Ministry Roles
        'is_pastor' => 'boolean',
        'is_intercessor' => 'boolean',
        'is_usher' => 'boolean',
        'is_worship_leader' => 'boolean',
        'is_sunday_school_teacher' => 'boolean',
        'is_offering_exhortation_leader' => 'boolean',
        'is_eligible_for_pulpit_ministry' => 'boolean',
    ];

    // Relationships
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function cellGroup()
    {
        return $this->belongsTo(CellGroup::class);
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function getAgeAttribute()
    {
        return $this->date_of_birth ? Carbon::parse($this->date_of_birth)->age : null;
    }

    public function getFormattedPhoneAttribute()
    {
        return $this->phone ? '+260' . $this->phone : null;
    }

    // Ministry Role Helper Methods
    public function getMinistryRolesAttribute()
    {
        $roles = [];
        if ($this->is_pastor) $roles[] = 'Pastor';
        if ($this->is_intercessor) $roles[] = 'Intercessor';
        if ($this->is_usher) $roles[] = 'Usher';
        if ($this->is_worship_leader) $roles[] = 'Worship Leader';
        if ($this->is_sunday_school_teacher) $roles[] = 'Sunday School Teacher';
        if ($this->is_offering_exhortation_leader) $roles[] = 'Offering/Exhortation Leader';
        if ($this->is_eligible_for_pulpit_ministry) $roles[] = 'Pulpit Ministry';

        return $roles;
    }

    public function hasMinistryRole()
    {
        return $this->is_pastor || $this->is_intercessor || $this->is_usher ||
               $this->is_worship_leader || $this->is_sunday_school_teacher ||
               $this->is_offering_exhortation_leader || $this->is_eligible_for_pulpit_ministry;
    }

    // Scopes for common filters
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function scopeByBranch($query, $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    public function scopeByMembershipStatus($query, $status)
    {
        return $query->where('membership_status', $status);
    }

    public function scopeJoinedBetween($query, $startDate, $endDate)
    {
        return $query->whereBetween('membership_date', [$startDate, $endDate]);
    }

    public function scopeAgeRange($query, $minAge, $maxAge)
    {
        $maxDate = now()->subYears($minAge);
        $minDate = now()->subYears($maxAge + 1);

        return $query->whereBetween('date_of_birth', [$minDate, $maxDate]);
    }

    public function scopeBaptized($query)
    {
        return $query->whereNotNull('baptism_date');
    }

    public function scopeHasSalvation($query)
    {
        return $query->whereNotNull('salvation_date');
    }

    public function scopeWithEmail($query)
    {
        return $query->whereNotNull('email');
    }

    public function scopeByGender($query, $gender)
    {
        return $query->where('gender', $gender);
    }

    public function scopeByMaritalStatus($query, $status)
    {
        return $query->where('marital_status', $status);
    }

    // Ministry Role Scopes
    public function scopePastors($query)
    {
        return $query->where('is_pastor', true);
    }

    public function scopeIntercessors($query)
    {
        return $query->where('is_intercessor', true);
    }

    public function scopeUshers($query)
    {
        return $query->where('is_usher', true);
    }

    public function scopeWorshipLeaders($query)
    {
        return $query->where('is_worship_leader', true);
    }

    public function scopeSundaySchoolTeachers($query)
    {
        return $query->where('is_sunday_school_teacher', true);
    }

    public function scopeOfferingExhortationLeaders($query)
    {
        return $query->where('is_offering_exhortation_leader', true);
    }

    public function scopeEligibleForPulpitMinistry($query)
    {
        return $query->where('is_eligible_for_pulpit_ministry', true);
    }

    public function scopeWithMinistryRoles($query)
    {
        return $query->where(function ($q) {
            $q->where('is_pastor', true)
              ->orWhere('is_intercessor', true)
              ->orWhere('is_usher', true)
              ->orWhere('is_worship_leader', true)
              ->orWhere('is_sunday_school_teacher', true)
              ->orWhere('is_offering_exhortation_leader', true)
              ->orWhere('is_eligible_for_pulpit_ministry', true);
        });
    }

    // Ministry Statistics
    public static function getMinistryStats()
    {
        return [
            'pastors' => self::pastors()->count(),
            'intercessors' => self::intercessors()->count(),
            'ushers' => self::ushers()->count(),
            'worship_leaders' => self::worshipLeaders()->count(),
            'sunday_school_teachers' => self::sundaySchoolTeachers()->count(),
            'offering_exhortation_leaders' => self::offeringExhortationLeaders()->count(),
            'pulpit_ministry_eligible' => self::eligibleForPulpitMinistry()->count(),
            'total_with_ministry' => self::withMinistryRoles()->count(),
        ];
    }
}

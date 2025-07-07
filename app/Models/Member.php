<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'branch_id',
        'registration_number',
        'title',                     // Mr, Mrs, Ms
        'first_name',
        'last_name',
        'date_of_birth',
        'gender',
        'phone',
        'alternative_phone',
        'email',
        'address',
        'marital_status',           // Single, Married, Widow
        'occupation',
        'employer',

        // Church-specific information
        'membership_date',
        'membership_status',        // Active, Inactive, Transferred, Deceased
        'previous_church',
        'previous_church_pastor',
        'salvation_date',
        'baptism_date',
        'baptism_type',            // Water, Holy Spirit

        // Growth Cycle Tracking
        'membership_class_status',  // Not Started, In Progress, Completed
        'foundation_class_status',
        'leadership_class_status',
        'cell_group_id',

        // Additional Information
        'emergency_contact_name',
        'emergency_contact_phone',
        'blood_group',
        'special_needs',
        'skills_talents',
        'interests',

        // Administrative
        'is_active',
        'deactivation_reason',
        'notes',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'membership_date' => 'date',
        'salvation_date' => 'date',
        'baptism_date' => 'date',
        'is_active' => 'boolean',
    ];

    protected $appends = ['full_name'];

   public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function cellGroup()
    {
        return $this->belongsTo(CellGroup::class);
    }

    public function departments()
    {
        return $this->belongsToMany(Department::class, 'department_members')
            ->withPivot(['role', 'joined_date', 'status']);
    }

    public function growthTrackRecords()
    {
        return $this->hasMany(GrowthTrackRecord::class);
    }

    public function familyMembers()
    {
        return $this->hasMany(MemberFamily::class);
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class);
    }

    public function getAgeAttribute()
    {
        return $this->date_of_birth ? Carbon::parse($this->date_of_birth)->age : null;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($member) {
            if (!$member->registration_number) {
                $latestMember = self::withTrashed()->latest('id')->first();
                $nextId = $latestMember ? $latestMember->id + 1 : 1;
                $member->registration_number = 'MEM-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
            }
        });
    }
}

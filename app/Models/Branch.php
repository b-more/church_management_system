<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'branch_code',
        'region_id',
        'address',
        'city',
        'country',
        'phone',
        'email',
        'senior_pastor_id',
        'district_pastor_id',
        'founding_date',
        'status', // Active, Inactive, Under Construction
        'service_times', // JSON field for service schedules
        'vision',
        'mission',
        'branch_type', // Main, Satellite, Campus Church
        'seating_capacity',
        'gps_coordinates',
        'notes'
    ];

    protected $casts = [
        'founding_date' => 'date',
        'service_times' => 'array'
    ];

    // Relationships
    public function seniorPastor()
    {
        return $this->belongsTo(Member::class, 'senior_pastor_id');
    }

    public function districtPastor()
    {
        return $this->belongsTo(Member::class, 'district_pastor_id');
    }

    // public function region()
    // {
    //     return $this->belongsTo(Region::class);
    // }

    public function members()
    {
        return $this->hasMany(Member::class);
    }

    public function cellGroups()
    {
        return $this->hasMany(CellGroup::class);
    }

    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }
}

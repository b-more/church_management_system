<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'branch_id',
        'name',
        'code',
        'description',
        'head_id', // Department head
        'assistant_head_id',
        'type', // Ministry, Administrative, Service
        'category', // Worship, Ushering, Protocol, Children, etc.
        'meeting_schedule',
        'responsibilities',
        'requirements',
        'status', // Active, Inactive
        'budget_allocation',
        'reports_to', // Superior department if any
        'notes'
    ];

    // Relationships
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function head()
    {
        return $this->belongsTo(Member::class, 'head_id');
    }

    public function assistantHead()
    {
        return $this->belongsTo(Member::class, 'assistant_head_id');
    }

    public function members()
    {
        return $this->belongsToMany(Member::class, 'department_members')
            ->withPivot(['role', 'joined_date', 'status', 'notes'])
            ->withTimestamps();
    }

    public function events()
    {
       // return $this->hasMany(Event::class);
    }

    public function superiorDepartment()
    {
        return $this->belongsTo(Department::class, 'reports_to');
    }

    public function subordinateDepartments()
    {
        return $this->hasMany(Department::class, 'reports_to');
    }
}

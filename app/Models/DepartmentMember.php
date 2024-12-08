<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepartmentMember extends Model
{
    protected $fillable = [
        'department_id',
        'member_id',
        'role',
        'joined_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'joined_date' => 'date',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
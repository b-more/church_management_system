<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberFamily extends Model
{
    protected $fillable = [
        'member_id',
        'relationship_type',   // Spouse, Child, Parent, Sibling
        'relative_name',
        'date_of_birth',
        'is_member',          // Boolean to indicate if also a church member
        'related_member_id',   // If is_member is true
        'notes'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'is_member' => 'boolean',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function relatedMember()
    {
        return $this->belongsTo(Member::class, 'related_member_id');
    }
}

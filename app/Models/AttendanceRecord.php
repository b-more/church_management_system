<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttendanceRecord extends Model 
{
   use SoftDeletes;

   protected $fillable = [
       'branch_id',
       'service_id',
       'member_id',
       'attendance_type',
       'check_in_time', 
       'check_out_time',
       'visitor_name',
       'visitor_phone',
       'visitor_address',
       'age_group',
       'gender',
       'previous_church',
       'checked_in_by',
       'follow_up_required',
       'follow_up_notes',
       'notes'
   ];

   protected $casts = [
       'check_in_time' => 'datetime',
       'check_out_time' => 'datetime',
       'follow_up_required' => 'boolean'
   ];

   public function branch()
   {
       return $this->belongsTo(Branch::class);
   }

   public function service() 
   {
       return $this->belongsTo(Service::class);
   }

   public function member()
   {
       return $this->belongsTo(Member::class);
   }

   public function checkedInBy()
   {
       return $this->belongsTo(User::class, 'checked_in_by');
   }
}

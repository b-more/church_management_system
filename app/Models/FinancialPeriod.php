<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class FinancialPeriod extends Model
{
   // use SoftDeletes;

    protected $fillable = [
        'start_date',
        'end_date',
        'type', // monthly, quarterly, annual
        'status', // open, closed
        'closed_by',
        'closed_at',
        'notes'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'closed_at' => 'datetime'
    ];

    public function closedByUser()
    {
        return $this->belongsTo(User::class, 'closed_by');
    }
}

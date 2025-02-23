<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    //use SoftDeletes;

    protected $fillable = [
        'category_id',
        'period_id',
        'amount',
        'notes'
    ];

    protected $casts = [
        'amount' => 'decimal:2'
    ];

    public function category()
    {
        return $this->belongsTo(TransactionCategory::class);
    }

    public function period()
    {
        return $this->belongsTo(FinancialPeriod::class);
    }
}

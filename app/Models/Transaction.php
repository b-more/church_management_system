<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    //use SoftDeletes;

    protected $fillable = [
        'branch_id',
        'member_id',
        'transaction_type', // tithe, offering, expense, donation
        'amount',
        'payment_method', // cash, mobile_money, bank_transfer
        'payment_reference',
        'transaction_date',
        'description',
        'category_id',
        'recorded_by',
        'status', // pending, completed, failed, reversed
        'notes'
    ];

    protected $casts = [
        'transaction_date' => 'datetime',
        'amount' => 'decimal:2'
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function category()
    {
        return $this->belongsTo(TransactionCategory::class);
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}

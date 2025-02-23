<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionCategory extends Model
{
    //use SoftDeletes;

    protected $fillable = [
        'name',
        'type', // income, expense
        'description',
        'is_active',
        'parent_id', // for hierarchical categories
        'budget_allocation'
    ];

    public function parent()
    {
        return $this->belongsTo(TransactionCategory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(TransactionCategory::class, 'parent_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'category_id');
    }
}

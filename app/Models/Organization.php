<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = ['organization_id', 'name', 'ussd_code'];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function members()
    {
        return $this->hasMany(Member::class);
    }

    public function offeringTypes()
    {
        return $this->hasMany(OfferingType::class);
    }

    public function offerings()
    {
        return $this->hasMany(Offering::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}

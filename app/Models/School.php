<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $fillable = [
        'school_name',
        'school_domain',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function bays()
    {
        return $this->hasMany(Bay::class);
    }
}

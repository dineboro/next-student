<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'school_id',
        'vehicle_vin',
        'make',
        'model',
        'year',
        'color',
        'status',
        'notes',
        'last_maintenance_at',
    ];

    protected $casts = [
        'last_maintenance_at' => 'datetime',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function helpRequests()
    {
        return $this->hasMany(HelpRequest::class);
    }
}

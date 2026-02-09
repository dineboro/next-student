<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bay extends Model
{
    protected $fillable = [
        'school_id',
        'bay_number',
        'bay_name',
        'status',
        'equipment',
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

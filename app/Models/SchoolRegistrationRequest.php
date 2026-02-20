<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolRegistrationRequest extends Model
{
    protected $fillable = [
        'school_name',
        'school_domain',
        'address',
        'contact_info',
        'registration_id',
        'requester_email',
        'requester_name',
        'requester_phone',
        'status',
        'rejection_reason',
        'reviewed_at',
        'reviewed_by',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

}

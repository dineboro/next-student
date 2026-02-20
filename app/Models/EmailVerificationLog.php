<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailVerificationLog extends Model
{
    protected $fillable = [
        'user_id',
        'verification_code',
        'ip_address',
        'action',
        'action_at',
    ];

    protected $casts = [
        'action_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

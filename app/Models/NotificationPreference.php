<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationPreference extends Model
{
    protected $fillable = [
        'user_id',
        'sms_enabled',
        'email_enabled',
        'in_app_enabled',
        'notify_on_request_assigned',
        'notify_on_request_completed',
        'notify_on_new_comment',
        'notify_on_status_change',
    ];

    protected $casts = [
        'sms_enabled' => 'boolean',
        'email_enabled' => 'boolean',
        'in_app_enabled' => 'boolean',
        'notify_on_request_assigned' => 'boolean',
        'notify_on_request_completed' => 'boolean',
        'notify_on_new_comment' => 'boolean',
        'notify_on_status_change' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

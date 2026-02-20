<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSettings extends Model
{
    protected $fillable = [
        'user_id',
        'notify_email',
        'notify_sms',
        'notify_push',
        'notify_request_assigned',
        'notify_request_updated',
        'notify_request_completed',
        'notify_new_comment',
        'profile_visible',
        'show_email',
        'show_phone',
        'allow_messages',
        'theme',
        'language',
    ];

    protected $casts = [
        'notify_email' => 'boolean',
        'notify_sms' => 'boolean',
        'notify_push' => 'boolean',
        'notify_request_assigned' => 'boolean',
        'notify_request_updated' => 'boolean',
        'notify_request_completed' => 'boolean',
        'notify_new_comment' => 'boolean',
        'profile_visible' => 'boolean',
        'show_email' => 'boolean',
        'show_phone' => 'boolean',
        'allow_messages' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}

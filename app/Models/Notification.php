<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'help_request_id',
        'type',
        'title',
        'message',
        'is_read',
        'sent_at',
        'read_at',
        'metadata',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'sent_at' => 'datetime',
        'read_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function helpRequest()
    {
        return $this->belongsTo(HelpRequest::class);
    }
}

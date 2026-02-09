<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'help_request_id',
        'user_id',
        'message',
        'is_internal',
    ];

    protected $casts = [
        'is_internal' => 'boolean',
    ];

    public function helpRequest()
    {
        return $this->belongsTo(HelpRequest::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

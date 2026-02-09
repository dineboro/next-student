<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QueuePosition extends Model
{
    protected $fillable = [
        'help_request_id',
        'position',
        'estimated_wait_minutes',
    ];

    public function helpRequest()
    {
        return $this->belongsTo(HelpRequest::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestAttachment extends Model
{
    protected $fillable = [
        'help_request_id',
        'uploaded_by',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
        'description',
    ];

    public function helpRequest()
    {
        return $this->belongsTo(HelpRequest::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = [
        'help_request_id',
        'student_id',
        'instructor_id',
        'rating',
        'feedback',
    ];

    public function helpRequest()
    {
        return $this->belongsTo(HelpRequest::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }
}

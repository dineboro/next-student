<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HelpRequest extends Model
{
    protected $fillable = [
        'student_id', 'vehicle_id', 'bay_id', 'category_id', 'assigned_instructor_id',
        'title', 'description', 'priority_level', 'status', 'assigned_at',
        'started_at', 'completed_at', 'cancelled_at', 'cancellation_reason',
        'resolution_notes', 'estimated_duration_minutes'
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function assignedInstructor()
    {
        return $this->belongsTo(User::class, 'assigned_instructor_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function bay()
    {
        return $this->belongsTo(Bay::class);
    }

    public function category()
    {
        return $this->belongsTo(RequestCategory::class, 'category_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function attachments()
    {
        return $this->hasMany(RequestAttachment::class);
    }

    public function rating()
    {
        return $this->hasOne(Rating::class);
    }

    public function queuePosition()
    {
        return $this->hasOne(QueuePosition::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HelpRequest extends Model
{
    protected $fillable = [
        'student_id',
        'assigned_instructor_id',
        'class_section_id',
        'title',
        'description',
        'location',
        'image',
        'status',
        'completed_at',
        'cancelled_at',
        'cancellation_reason',
        'resolution_notes',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'pending');
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    public function statusBadgeClass(): string
    {
        return match($this->status) {
            'pending'   => 'bg-yellow-100 text-yellow-800',
            'completed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
            default     => 'bg-gray-100 text-gray-800',
        };
    }

    public function statusLabel(): string
    {
        return ucfirst($this->status);
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function assignedInstructor()
    {
        return $this->belongsTo(User::class, 'assigned_instructor_id');
    }

    public function classSection()
    {
        return $this->belongsTo(ClassSection::class, 'class_section_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->orderBy('created_at', 'asc');
    }

    public function attachments()
    {
        return $this->hasMany(RequestAttachment::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }
}

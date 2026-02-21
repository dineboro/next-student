<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable, HasRoles, SoftDeletes;

    protected $fillable = [
        'school_id',
        'first_name',
        'last_name',
        'email',
        'email_verified_at',
        'phone_number',
        'password',
        'role',
        'is_available',
        'student_id',
        'instructor_id',
        'profile_photo',
        'verification_code',
        'verification_code_expires_at',
        'approval_status',
        'rejection_reason',
        'approved_at',
        'approved_by',
        'bio',
        'privacy_settings',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_available' => 'boolean',
        'password' => 'hashed',
        'verification_code_expires_at' => 'datetime',
        'approved_at' => 'datetime',
        'privacy_settings' => 'array',
    ];

    // Relationships

    public function settings()
    {
        return $this->hasOne(UserSettings::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function approvedUsers()
    {
        return $this->hasMany(User::class, 'approved_by');
    }

    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }

    public function scopePendingApproval($query)
    {
        return $query->where('approval_status', 'pending');
    }

    public function isEmailVerified()
    {
        return !is_null($this->email_verified_at);
    }

    public function isApproved()
    {
        return $this->approval_status === 'approved';
    }
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function helpRequestsAsStudent()
    {
        return $this->hasMany(HelpRequest::class, 'student_id');
    }

    public function helpRequestsAsInstructor()
    {
        return $this->hasMany(HelpRequest::class, 'assigned_instructor_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function ratingsGiven()
    {
        return $this->hasMany(Rating::class, 'student_id');
    }

    public function ratingsReceived()
    {
        return $this->hasMany(Rating::class, 'instructor_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function notificationPreferences()
    {
        return $this->hasOne(NotificationPreference::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }
}

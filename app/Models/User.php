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
        'phone_number',
        'password',
        'role',
        'is_available',
        'student_id',
        'instructor_id',
        'profile_photo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_available' => 'boolean',
        'password' => 'hashed',
    ];

    // Relationships
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

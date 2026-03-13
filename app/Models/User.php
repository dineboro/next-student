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
        'first_name',
        'last_name',
        'email',
        'email_verified_at',
        'phone_number',
        'password',
        'role',
        'major',
        'department',
        'badge_photo',
        'student_id',
        'instructor_id',
        'profile_photo',
        'verification_code',
        'verification_code_expires_at',
        'approval_status',
        'rejection_reason',
        'approved_at',
        'approved_by',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at'             => 'datetime',
        'password'                      => 'hashed',
        'verification_code_expires_at'  => 'datetime',
        'approved_at'                   => 'datetime',
    ];

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }

    public function scopePendingApproval($query)
    {
        return $query->where('approval_status', 'pending');
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    public function isEmailVerified(): bool
    {
        return !is_null($this->email_verified_at);
    }

    public function isApproved(): bool
    {
        return $this->approval_status === 'approved';
    }

    public function fullName(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function departmentLabel(): string
    {
        return match($this->department) {
            'business_it'       => 'Business & IT',
            'liberal_arts'      => 'Liberal Arts',
            'science'           => 'Science',
            'health_sciences'   => 'Health Sciences',
            'trades_technology' => 'Trades & Technology',
            'arts_humanities'   => 'Arts & Humanities',
            default             => ucfirst($this->department ?? ''),
        };
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Student — class sessions they are enrolled in
    public function enrolledSections()
    {
        return $this->belongsToMany(
            ClassSection::class,
            'class_section_students',
            'student_id',
            'class_section_id'
        )->withTimestamps();
    }

    // Instructor — class sessions they own
    public function classSections()
    {
        return $this->hasMany(ClassSection::class, 'instructor_id');
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

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function settings()
    {
        return $this->hasOne(UserSettings::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassSection extends Model
{
    protected $fillable = [
        'instructor_id',
        'course_name',
        'course_code',
        'semester',
        'year',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'year'      => 'integer',
    ];

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    public function semesterLabel(): string
    {
        return "{$this->semester} {$this->year}";
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function students()
    {
        return $this->belongsToMany(
            User::class,
            'class_section_students',
            'class_section_id',
            'student_id'
        )->withTimestamps();
    }

    public function helpRequests()
    {
        return $this->hasMany(HelpRequest::class, 'class_section_id');
    }
}

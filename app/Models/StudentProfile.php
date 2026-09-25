<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'student_id',
        'course',
        'year_level',
        'gpa',
        'skills',
        'interests',
        'resume',
        'university',
        'graduation_date',
        'saved_opportunities_count',
        'applications_count',
    ];

    protected $casts = [
        'graduation_date' => 'date',
        'skills' => 'array',
        'interests' => 'array',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper methods
    public function getSkillsArray()
    {
        return is_array($this->skills) ? $this->skills : explode(',', $this->skills ?? '');
    }

    public function getInterestsArray()
    {
        return is_array($this->interests) ? $this->interests : explode(',', $this->interests ?? '');
    }
}

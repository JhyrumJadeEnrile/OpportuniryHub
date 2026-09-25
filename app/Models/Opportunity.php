<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Opportunity extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id',
        'title',
        'description',
        'type',
        'status',
        'start_date',
        'application_deadline',
        'end_date',
        'location',
        'salary_range',
        'duration',
        'positions_available',
        'applications_received',
        'requirements',
        'benefits',
        'course_eligibility',
        'tags',
        'featured_image',
        'is_featured',
        'views_count',
        'saves_count',
        'published_at',
    ];

    protected $casts = [
        'start_date' => 'date',
        'application_deadline' => 'date',
        'end_date' => 'date',
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
    ];

    // Relationships
    public function provider()
    {
        return $this->belongsTo(ProviderProfile::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function savedBy()
    {
        return $this->belongsToMany(User::class, 'saved_opportunities', 'opportunity_id', 'student_id')
            ->withTimestamps();
    }

    public function reminders()
    {
        return $this->hasMany(DeadlineReminder::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('application_deadline', '>', now())
            ->orderBy('application_deadline', 'asc');
    }

    public function scopeClosingSoon($query)
    {
        $sevenDaysFromNow = now()->addDays(7);
        return $query->whereBetween('application_deadline', [now(), $sevenDaysFromNow])
            ->orderBy('application_deadline', 'asc');
    }

    // Helper methods
    public function getTagsArray()
    {
        return array_filter(explode(',', $this->tags ?? ''));
    }

    public function isDeadlinePassed()
    {
        return now()->isAfter($this->application_deadline);
    }

    public function daysUntilDeadline()
    {
        return now()->diffInDays($this->application_deadline, false);
    }

    public function getDeadlineStatus()
    {
        $days = $this->daysUntilDeadline();
        
        if ($days < 0) return 'closed';
        if ($days === 0) return 'closing_today';
        if ($days <= 3) return 'closing_soon';
        if ($days <= 7) return 'one_week_left';
        
        return 'open';
    }

    public function hasApplications()
    {
        return $this->applications()->exists();
    }

    public function getApplicationCount()
    {
        return $this->applications()->count();
    }
}

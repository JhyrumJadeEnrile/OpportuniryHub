<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'opportunity_id',
        'status',
        'cover_letter',
        'resume',
        'application_answers',
        'admin_notes',
        'reviewed_at',
        'submitted_at',
    ];

    protected $casts = [
        'application_answers' => 'json',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function opportunity()
    {
        return $this->belongsTo(Opportunity::class);
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeUnreviewed($query)
    {
        return $query->where('status', 'submitted');
    }

    // Helper methods
    public function isUnreviewed()
    {
        return $this->status === 'submitted';
    }

    public function isAccepted()
    {
        return $this->status === 'accepted';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function isShortlisted()
    {
        return $this->status === 'shortlisted';
    }

    public function getStatusBadgeClass()
    {
        return match($this->status) {
            'submitted' => 'badge-info',
            'under_review' => 'badge-warning',
            'shortlisted' => 'badge-success',
            'accepted' => 'badge-success',
            'rejected' => 'badge-danger',
            default => 'badge-secondary',
        };
    }
}

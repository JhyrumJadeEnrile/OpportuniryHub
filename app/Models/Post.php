<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    public const GENERAL = [
        'announcement' => 'Announcement',
        'job'          => 'Job opening',
        'internship'   => 'Internship',
        'event'        => 'Event',
        'training'     => 'Training / Seminar',
        'volunteer'    => 'Volunteer',
        'achievement'  => 'Achievement',
        'question'     => 'Question',
    ];

    public const OPPORTUNITY = [
        'hiring'      => 'Hiring',
        'application' => 'Application',
        'scholarship' => 'Scholarship',
        'other'       => 'Other application',
    ];

    public const CATEGORIES = self::GENERAL + self::OPPORTUNITY;

    protected $fillable = [
        'user_id',
        'category',
        'body',
        'attachment_path',
        'attachment_name',
        'deadline',
        'status',
        'requirement_path',
        'requirement_name',
    ];

    protected function casts(): array
    {
        return [
            'deadline' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->oldest();
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class)->latest();
    }

    public function isOpportunity(): bool
    {
        return array_key_exists($this->category, self::OPPORTUNITY);
    }

    public function isScholarship(): bool
    {
        return $this->category === 'scholarship';
    }

    public function isOpen(): bool
    {
        return $this->isOpportunity()
            && $this->status === 'open'
            && (! $this->deadline || $this->deadline->endOfDay()->isFuture());
    }

    /**
     * Returns the reason this user cannot apply, or null if they can.
     */
    public function applyBlockReason(User $user): ?string
    {
        if ($this->user_id === $user->id) {
            return 'This is your own post.';
        }

        if (! $this->isOpen()) {
            return 'Applications are closed.';
        }

        if ($this->isScholarship() && $user->member_type !== 'student') {
            return 'Scholarships are open to students only.';
        }

        return null;
    }

    public function attachmentUrl(): ?string
    {
        return $this->attachment_path ? Storage::disk('public')->url($this->attachment_path) : null;
    }

    public function scopeOpportunities(Builder $query): Builder
    {
        return $query->whereIn('category', array_keys(self::OPPORTUNITY));
    }

    public function scopeOpen(Builder $query): Builder
    {
        return $query->where('status', 'open')
            ->where(function (Builder $q) {
                $q->whereNull('deadline')->orWhereDate('deadline', '>=', now()->toDateString());
            });
    }
}
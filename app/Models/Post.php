<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    public const CATEGORIES = [
        'announcement' => 'Announcement',
        'job'          => 'Job opening',
        'internship'   => 'Internship',
        'scholarship'  => 'Scholarship',
        'event'        => 'Event',
        'training'     => 'Training / Seminar',
        'volunteer'    => 'Volunteer',
        'achievement'  => 'Achievement',
        'question'     => 'Question',
    ];

    protected $fillable = [
        'user_id',
        'category',
        'body',
        'attachment_path',
        'attachment_name',
    ];

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

    public function attachmentUrl(): ?string
    {
        return $this->attachment_path ? Storage::disk('public')->url($this->attachment_path) : null;
    }
}
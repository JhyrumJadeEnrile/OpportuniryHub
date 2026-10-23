<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $fillable = [
        'sender_id',
        'recipient_id',
        'listing_id',
        'body',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function listing(): BelongsTo
    {
        return $this->belongsTo(Listing::class);
    }

    /** All messages exchanged between two users, oldest first. */
    public function scopeBetweenUsers(Builder $query, int $a, int $b): Builder
    {
        return $query->where(function (Builder $q) use ($a, $b) {
            $q->where('sender_id', $a)->where('recipient_id', $b);
        })->orWhere(function (Builder $q) use ($a, $b) {
            $q->where('sender_id', $b)->where('recipient_id', $a);
        })->orderBy('created_at');
    }
}

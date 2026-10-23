<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const TYPES = [
        'student'   => 'Student',
        'fresh_grad' => 'Fresh Grad',
        'alumni'    => 'Alumni',
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
        'member_type',
        'batch_year',
        'program',
        'headline',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function listings(): HasMany
    {
        return $this->hasMany(Listing::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'recipient_id');
    }

    public function typeLabel(): string
    {
        return self::TYPES[$this->member_type] ?? 'Member';
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'bio',
        'avatar',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
        ];
    }

    // Role checks
    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    public function isProvider(): bool
    {
        return $this->role === 'provider';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // Relationships
    public function studentProfile()
    {
        return $this->hasOne(StudentProfile::class);
    }

    public function providerProfile()
    {
        return $this->hasOne(ProviderProfile::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class, 'student_id');
    }

    public function savedOpportunities()
    {
        return $this->belongsToMany(Opportunity::class, 'saved_opportunities', 'student_id', 'opportunity_id')
            ->withTimestamps();
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function deadlineReminders()
    {
        return $this->hasMany(DeadlineReminder::class, 'student_id');
    }

    public function unreadNotifications()
    {
        return $this->notifications()->where('is_read', false)->orderBy('created_at', 'desc');
    }

    public function getUnreadNotificationCount()
    {
        return $this->unreadNotifications()->count();
    }
}

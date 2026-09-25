<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeadlineReminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'opportunity_id',
        'reminder_type',
        'send_at',
        'is_sent',
        'sent_at',
    ];

    protected $casts = [
        'send_at' => 'datetime',
        'sent_at' => 'datetime',
        'is_sent' => 'boolean',
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
    public function scopePending($query)
    {
        return $query->where('is_sent', false)
            ->where('send_at', '<=', now());
    }

    public function scopeSent($query)
    {
        return $query->where('is_sent', true);
    }

    // Helper methods
    public function markAsSent()
    {
        $this->update([
            'is_sent' => true,
            'sent_at' => now(),
        ]);
    }
}

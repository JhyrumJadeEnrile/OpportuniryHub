<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'company_description',
        'company_logo',
        'industry',
        'website',
        'contact_email',
        'contact_phone',
        'company_address',
        'city',
        'country',
        'total_opportunities',
        'active_opportunities',
        'is_verified',
        'verified_at',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function opportunities()
    {
        return $this->hasMany(Opportunity::class);
    }

    public function getActiveOpportunitiesCount()
    {
        return $this->opportunities()->where('status', 'active')->count();
    }
}

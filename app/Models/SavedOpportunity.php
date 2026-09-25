<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class SavedOpportunity extends Pivot
{
    use HasFactory;

    protected $table = 'saved_opportunities';

    protected $casts = [
        'saved_at' => 'datetime',
    ];

    public $incrementing = true;
}

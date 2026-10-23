<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Listing extends Model
{
    public const CATEGORIES = [
        'books'       => 'Books',
        'electronics' => 'Electronics',
        'clothing'    => 'Clothing',
        'furniture'   => 'Furniture',
        'services'    => 'Services',
        'others'      => 'Others',
    ];

    public const CONDITIONS = [
        'new'      => 'New',
        'like_new' => 'Like new',
        'used'     => 'Used',
    ];

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'category',
        'price',
        'condition',
        'image_path',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('status', 'available');
    }

    public function imageUrl(): ?string
    {
        return $this->image_path ? Storage::disk('public')->url($this->image_path) : null;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tank extends Model
{
    protected $fillable = [
        'depot_id',
        'product_id',
        'tag',
        'capacity_litres',
        'current_volume',
    ];

    protected $casts = [
        'capacity_litres' => 'decimal:2',
        'current_volume' => 'decimal:2',
    ];

    public function depot(): BelongsTo
    {
        return $this->belongsTo(Depot::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function receipts(): HasMany
    {
        return $this->hasMany(Receipt::class);
    }

    public function dispatches(): HasMany
    {
        return $this->hasMany(Dispatch::class);
    }

    protected function fillPercentage(): Attribute
    {
        return Attribute::make(
            get: fn () => round(
                ($this->current_volume / $this->capacity_litres) * 100,
                2
            )
        );
    }
}

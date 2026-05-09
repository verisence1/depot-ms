<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Receipt extends Model
{
    use HasFactory;
    protected $fillable = [
        'depot_id',
        'tank_id',
        'product_id',
        'tanker_id',
        'volume',
        'receipt_date',
        'batch_ref',
    ];

    protected $casts = [
        'volume' => 'decimal:2',
        'receipt_date' => 'date',
    ];

    public function depot(): BelongsTo
    {
        return $this->belongsTo(Depot::class);
    }

    public function tank(): BelongsTo
    {
        return $this->belongsTo(Tank::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function tanker(): BelongsTo
    {
        return $this->belongsTo(Tanker::class);
    }
}

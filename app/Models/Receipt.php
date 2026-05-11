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
        'status',
        'created_by',
        'updated_by',
        'approved_by',
        'approved_at',
        'reversed_at',
        'reversed_by',
    ];

 protected $casts = [
    'volume' => 'decimal:2',
    'receipt_date' => 'date',
    'approved_at' => 'datetime',
    'reversed_at' => 'datetime',
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
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function reverser()
    {
        return $this->belongsTo(User::class, 'reversed_by');
    }
}

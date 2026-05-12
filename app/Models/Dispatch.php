<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dispatch extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_CANCELLED = 'cancelled';

    public static function statuses(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_APPROVED,
            self::STATUS_CANCELLED,
        ];
    }

    protected $fillable = [
        'depot_id',
        'tank_id',
        'product_id',
        'tanker_id',
        'customer_id',
        'volume',
        'dispatch_date',
        'waybill_no',
        'status',
        'created_by',
        'updated_by',
        'approved_by',
        'approved_at',
        'cancelled_at',
        'cancelled_by',
    ];

    protected $casts = [
        'volume' => 'decimal:2',
        'dispatch_date' => 'date',
        'approved_at' => 'datetime',
        'cancelled_at' => 'datetime',
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

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
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

    public function canceller()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }
}

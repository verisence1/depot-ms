<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'tin',
        'contact_email',
        'credit_limit',
    ];

    protected $casts = [
        'credit_limit' => 'decimal:2',
    ];

    public function dispatches(): HasMany
    {
        return $this->hasMany(Dispatch::class);
    }
}

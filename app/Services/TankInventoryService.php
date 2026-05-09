<?php

namespace App\Services;

use App\Exceptions\InsufficientInventoryException;
use App\Exceptions\ProductMismatchException;
use App\Exceptions\TankOverflowException;
use App\Models\Tank;
use Illuminate\Support\Facades\DB;

class TankInventoryService
{
    public function credit(
        Tank $tank,
        int $productId,
        float $volume
    ): void {

        $this->ensureProductMatches(
            $tank,
            $productId
        );

        $newVolume =
            $tank->current_volume + $volume;

        if ($newVolume > $tank->capacity_litres) {
            throw new TankOverflowException(
                'Tank capacity exceeded.'
            );
        }

        DB::transaction(function () use (
            $tank,
            $newVolume
        ) {

            $tank->update([
                'current_volume' => $newVolume,
            ]);
        });
    }

    public function debit(
        Tank $tank,
        int $productId,
        float $volume
    ): void {

        $this->ensureProductMatches(
            $tank,
            $productId
        );

        if ($tank->current_volume < $volume) {
            throw new InsufficientInventoryException(
                'Insufficient inventory.'
            );
        }

        DB::transaction(function () use (
            $tank,
            $volume
        ) {

            $tank->update([
                'current_volume' =>
                    $tank->current_volume - $volume,
            ]);
        });
    }

    protected function ensureProductMatches(
        Tank $tank,
        int $productId
    ): void {

        if ($tank->product_id !== $productId) {

            throw new ProductMismatchException(
                'Tank product does not match request product.'
            );
        }
    }
}

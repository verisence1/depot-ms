<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InventoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'tank_tag' => $this->tag,

            'product' => $this->product?->name,

            'capacity_litres' => $this->capacity_litres,

            'current_volume' => $this->current_volume,

            'fill_percentage' => $this->fill_percentage,
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TankResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'tag' => $this->tag,

            'capacity_litres' => $this->capacity_litres,

            'current_volume' => $this->current_volume,

            'fill_percentage' => $this->fill_percentage,

            'product' => [
                'id' => $this->product?->id,
                'name' => $this->product?->name,
                'code' => $this->product?->code,
            ],
        ];
    }
}

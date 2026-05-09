<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DispatchResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'volume' => $this->volume,

            'dispatch_date' => $this->dispatch_date,

            'waybill_no' => $this->waybill_no,

            'tank' => $this->tank?->tag,

            'product' => $this->product?->name,

            'customer' => $this->customer?->name,
        ];
    }
}

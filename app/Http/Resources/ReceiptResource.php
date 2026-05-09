<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReceiptResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'volume' => $this->volume,

            'receipt_date' => $this->receipt_date,

            'batch_ref' => $this->batch_ref,

            'tank' => $this->tank?->tag,

            'product' => $this->product?->name,

            'tanker' => $this->tanker?->registration,
        ];
    }
}

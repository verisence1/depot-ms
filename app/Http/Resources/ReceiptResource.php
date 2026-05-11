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

            // Audit fields

            'created_by' => $this->creator?->name,
            'created_at' => $this->created_at,
            'approved_by' => $this->approver?->name,
            'approved_at' => $this->approved_at,
            'updated_by' => $this->updater?->name,
            'updated_at' => $this->updated_at,

            // Reversal fields

            'is_reversed' => $this->is_reversed,
            'reversed_by' => $this->reverser?->name,
            'reversed_at' => $this->reversed_at,
        ];
    }
}

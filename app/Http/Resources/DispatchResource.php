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

            'tanker' => $this->tanker?->registration,

            'customer' => $this->customer?->name,

            // Audit Fields

            'created_by' => $this->creator?->name,

            'created_at' => $this->created_at,

            'approved_by' => $this->approver?->name,

            'approved_at' => $this->approved_at,

            'updated_by' => $this->updater?->name,

            'updated_at' => $this->updated_at,

            // Cancellation Fields

            'is_cancelled' => $this->is_cancelled,

            'cancelled_by' => $this->canceller?->name,

            'cancelled_at' => $this->cancelled_at,
        ];
    }
}

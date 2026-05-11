<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'name' => $this->name,

            'tin' => $this->tin,

            'contact_email' => $this->contact_email,

            'credit_limit' => $this->credit_limit,

            'dispatches' => $this->dispatches->map(function ($dispatch) {

                return [

                    'id' => $dispatch->id,

                    'waybill_no' => $dispatch->waybill_no,

                    'volume' => $dispatch->volume,

                    'dispatch_date' => $dispatch->dispatch_date,

                    'status' => $dispatch->status,

                    'tank' => $dispatch->tank?->tag,

                    'product' => $dispatch->product?->name,

                    'tanker' => $dispatch->tanker?->registration,
                ];
            }),

            'created_at' => $this->created_at,

            'updated_at' => $this->updated_at,
        ];
    }
}

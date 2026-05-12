<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TankerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'registration' => $this->registration,

            'type' => $this->type,

            'capacity_litres' => $this->capacity_litres,

            'operator' => [
                'id' => $this->operator?->id,
                'name' => $this->operator?->name,
                'email' => $this->operator?->email,
            ],

            'dispatches' => $this->dispatches->map(function ($dispatch) {
                return [
                    'id' => $dispatch->id,
                    'waybill_no' => $dispatch->waybill_no,
                    'volume' => $dispatch->volume,
                    'dispatch_date' => $dispatch->dispatch_date,
                    'status' => $dispatch->status,
                    'customer' => $dispatch->customer?->name,
                    'tank' => $dispatch->tank?->tag,
                    'product' => $dispatch->product?->name,
                ];
            }),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

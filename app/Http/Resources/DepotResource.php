<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepotResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'name' => $this->name,

            'location' => $this->location,

            'license_number' => $this->license_number,

            'status' => $this->status,

            'tanks_count' => $this->whenCounted('tanks'),

            'created_at' => $this->created_at,
        ];
    }
}

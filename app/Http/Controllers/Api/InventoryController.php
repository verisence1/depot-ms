<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\InventoryResource;
use App\Models\Depot;

class InventoryController extends Controller
{
    public function index(Depot $depot)
    {
        $tanks = $depot->tanks()
            ->with([
                'product',
            ])
            ->latest()
            ->get();

        return InventoryResource::collection(
            $tanks
        );
    }
}

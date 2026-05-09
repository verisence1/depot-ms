<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DepotResource;
use App\Models\Depot;

class DepotController extends Controller
{
    public function index()
    {
        return DepotResource::collection(
            Depot::withCount('tanks')->get()
        );
    }

    public function show(Depot $depot)
    {
        return new DepotResource(
            $depot->load('tanks')
        );
    }
}

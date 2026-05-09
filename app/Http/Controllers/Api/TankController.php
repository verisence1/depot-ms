<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TankResource;
use App\Models\Tank;

class TankController extends Controller
{
    public function index()
    {
        return TankResource::collection(
            Tank::with([
                'product',
                'depot',
            ])->get()
        );
    }

    public function show(Tank $tank)
    {
        return new TankResource(
            $tank->load([
                'product',
                'depot',
            ])
        );
    }
}

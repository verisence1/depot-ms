<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TankResource;
use App\Models\Tank;
use Illuminate\Http\Request;

class TankController extends Controller
{
    public function index()
    {
        return TankResource::collection(
            Tank::with([
                'product',
                'depot',
            ])
                ->latest()
                ->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'depot_id' => [
                'required',
                'exists:depots,id',
            ],

            'product_id' => [
                'required',
                'exists:products,id',
            ],

            'tag' => [
                'required',
                'string',
                'unique:tanks,tag',
            ],

            'capacity_litres' => [
                'required',
                'numeric',
                'min:1',
            ],

            'current_volume' => [
                'required',
                'numeric',
                'min:0',
            ],
        ]);

        if (
            $validated['current_volume']
            > $validated['capacity_litres']
        ) {

            return response()->json([
                'message' =>
                    'Current volume cannot exceed tank capacity.',
            ], 422);
        }

        $tank = Tank::create($validated);

        return new TankResource(
            $tank->load([
                'product',
                'depot',
            ])
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

    public function update(
        Request $request,
        Tank $tank
    ) {

        $validated = $request->validate([
            'depot_id' => [
                'exists:depots,id',
            ],

            'product_id' => [
                'exists:products,id',
            ],

            'tag' => [
                'string',
                'unique:tanks,tag,' . $tank->id,
            ],

            'capacity_litres' => [
                'numeric',
                'min:1',
            ],

            'current_volume' => [
                'numeric',
                'min:0',
            ],
        ]);

        if (
            $validated['current_volume']
            > $validated['capacity_litres']
        ) {

            return response()->json([
                'message' =>
                    'Current volume cannot exceed tank capacity.',
            ], 422);
        }

        $tank->update($validated);

        return new TankResource(
            $tank->load([
                'product',
                'depot',
            ])
        );
    }

    public function destroy(Tank $tank)
    {
        $tank->delete();

        return response()->json([
            'message' => 'Tank deleted successfully.',
        ]);
    }
}

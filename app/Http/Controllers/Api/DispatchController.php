<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDispatchRequest;
use App\Http\Resources\DispatchResource;
use App\Models\Dispatch;
use App\Models\Tank;
use App\Services\TankInventoryService;

class DispatchController extends Controller
{
    public function __construct(
        protected TankInventoryService $inventoryService
    ) {
    }

    public function index()
    {
        return DispatchResource::collection(
            Dispatch::with([
                'tank',
                'product',
                'customer',
            ])
                ->latest()
                ->get()
        );
    }

    public function store(
        StoreDispatchRequest $request
    ) {

        $tank = Tank::findOrFail(
            $request->tank_id
        );

        $this->inventoryService->debit(
            $tank,
            $request->product_id,
            $request->volume
        );

        $dispatch = Dispatch::create(
            $request->validated()
        );

        return new DispatchResource(
            $dispatch->load([
                'tank',
                'product',
                'customer',
            ])
        );
    }

    public function show(Dispatch $dispatch)
    {
        return new DispatchResource(
            $dispatch->load([
                'tank',
                'product',
                'customer',
            ])
        );
    }

    public function update()
    {
        return response()->json([
            'message' =>
                'Dispatch updates are not allowed.',
        ], 405);
    }

    public function destroy()
    {
        return response()->json([
            'message' =>
                'Dispatch deletion is not allowed.',
        ], 405);
    }
}

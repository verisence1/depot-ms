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
                'tanker',
                'customer',
                'creator',
                'approver',
                'canceller',
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

        $dispatch = Dispatch::create([
            ...$request->validated(),
            'created_by' => auth()->id(),
        ]);

        return new DispatchResource(
            $dispatch->load([
                'tank',
                'product',
                'tanker',
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
                'tanker',
                'customer',
                'creator',
                'approver',
                'canceller',
            ])
        );
    }

    public function approve(Dispatch $dispatch)
    {
        if ($dispatch->approved_by) {

            return response()->json([
                'message' => 'Dispatch already approved.'
            ], 422);
        }

        $dispatch->update([
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return new DispatchResource(
            $dispatch->fresh()->load([
                'tank',
                'product',
                'tanker',
                'customer',
                'creator',
                'approver',
            ])
        );
    }

    public function cancel(Dispatch $dispatch)
    {
        if ($dispatch->is_cancelled) {

            return response()->json([
                'message' => 'Dispatch already cancelled.'
            ], 422);
        }

        $tank = $dispatch->tank;

        if (
            ($tank->current_volume + $dispatch->volume)
            > $tank->capacity_litres
        ) {

            return response()->json([
                'message' =>
                    'Tank capacity exceeded during cancellation.'
            ], 422);
        }

        $tank->increment(
            'current_volume',
            $dispatch->volume
        );

        $dispatch->update([
            'is_cancelled' => true,
            'cancelled_at' => now(),
            'cancelled_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        return new DispatchResource(
            $dispatch->fresh()->load([
                'tank',
                'product',
                'tanker',
                'customer',
                'creator',
                'approver',
                'canceller',
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

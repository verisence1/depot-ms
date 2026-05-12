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

        if ($tank->depot->status !== 'active') {
            return response()->json([
                'message' => 'Inactive depots cannot dispatch inventory.'
            ], 422);
        }

        if ($tank->product_id !== (int) $request->product_id) {
            return response()->json([
                'message' => 'Tank product does not match request product.'
            ], 422);
        }

        if ($request->volume > $tank->current_volume) {

            return response()->json([
                'message' => 'Insufficient tank inventory for dispatch.'
            ], 422);
        }

        $dispatch = Dispatch::create([
            ...$request->validated(),
            'created_by' => auth()->id(),
            'status' => Dispatch::STATUS_PENDING,
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
        if ($dispatch->status !== Dispatch::STATUS_PENDING) {

            return response()->json([
                'message' =>
                    'Only pending dispatches can be approved.'
            ], 422);
        }

        if ($dispatch->depot->status !== 'active') {
            return response()->json([
                'message' =>
                    'Cannot approve dispatches for inactive depots.'
            ], 422);
        }

        $tank = $dispatch->tank;

        $this->inventoryService->debit(
            $tank,
            $dispatch->product_id,
            $dispatch->volume
        );

        $dispatch->update([
            'status' => Dispatch::STATUS_APPROVED,
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
        if ($dispatch->status !== Dispatch::STATUS_APPROVED) {

            return response()->json([
                'message' =>
                    'Only approved dispatches can be cancelled.'
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
            'status' => Dispatch::STATUS_CANCELLED,
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

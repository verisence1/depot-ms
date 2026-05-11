<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReceiptRequest;
use App\Http\Resources\ReceiptResource;
use App\Models\Receipt;
use App\Models\Tank;
use App\Services\TankInventoryService;

class ReceiptController extends Controller
{
    public function __construct(
        protected TankInventoryService $inventoryService
    ) {
    }

    public function index()
    {
        return ReceiptResource::collection(
            Receipt::with([
                'tank',
                'product',
                'tanker',
            ])
                ->latest()
                ->get()
        );
    }

    public function store(
        StoreReceiptRequest $request
    ) {

        $tank = Tank::findOrFail(
            $request->tank_id
        );

        if ($tank->product_id !== (int) $request->product_id) {
            return response()->json([
                'message' => 'Tank product does not match request product.'
            ], 422);
        }

        if (
            ($tank->current_volume + $request->volume)
            > $tank->capacity_litres
        ) {

            return response()->json([
                'message' => 'Tank capacity exceeded.'
            ], 422);
        }

        $receipt = Receipt::create([
            ...$request->validated(),
            'created_by' => auth()->id(),
            'status' => 'pending',
        ]);

        return new ReceiptResource(
            $receipt->load([
                'tank',
                'product',
                'tanker',
                'creator',
            ])
        );
    }

    public function show(Receipt $receipt)
    {
        return new ReceiptResource(
            $receipt->load([
                'tank',
                'product',
                'tanker',
            ])
        );
    }

    public function approve(Receipt $receipt)
    {
        if ($receipt->status !== 'pending') {
            return response()->json([
                'message' =>
                    'Only pending receipts can be approved.'
            ], 422);

        }

        $tank = $receipt->tank;

        $this->inventoryService->credit(
            $tank,
            $receipt->product_id,
            $receipt->volume
        );

        $receipt->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return new ReceiptResource(
            $receipt->fresh()->load([
                'tank',
                'product',
                'tanker',
                'creator',
                'approver',
            ])
        );
    }

    public function reverse(Receipt $receipt)
    {
        if ($receipt->status !== 'approved') {

            return response()->json([
                'message' =>
                    'Only approved receipts can be reversed.'
            ], 422);
        }

        $tank = $receipt->tank;

        if ($tank->current_volume < $receipt->volume) {

            return response()->json([
                'message' =>
                    'Tank inventory insufficient for reversal.'
            ], 422);
        }

        $tank->decrement(
            'current_volume',
            $receipt->volume
        );

        $receipt->update([
            'status' => 'reversed',

            'reversed_at' => now(),

            'reversed_by' => auth()->id(),

            'updated_by' => auth()->id(),
        ]);

        return new ReceiptResource(
            $receipt->fresh()->load([
                'tank',
                'product',
                'tanker',
                'creator',
                'approver',
                'reverser',
            ])
        );
    }

    public function update()
    {
        return response()->json([
            'message' =>
                'Receipt updates are not allowed.',
        ], 405);
    }

    public function destroy()
    {
        return response()->json([
            'message' =>
                'Receipt deletion is not allowed.',
        ], 405);
    }
}

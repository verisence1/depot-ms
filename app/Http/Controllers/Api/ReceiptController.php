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

        $this->inventoryService->credit(
            $tank,
            $request->product_id,
            $request->volume
        );

        $receipt = Receipt::create([
            ...$request->validated(),
            'created_by' => auth()->id(),
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
        if ($receipt->approved_by) {
            return response()->json([
                'message' => 'Receipt already approved.'
            ], 422);
        }

        $receipt->update([
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return new ReceiptResource(
            $receipt->fresh()->load([
                'tank',
                'product',
                'tanker',
                'approver',
                'creator',
            ])
        );
    }

 public function reverse(Receipt $receipt)
{
    if ($receipt->is_reversed) {
        return response()->json([
            'message' => 'Receipt already reversed.'
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
        'is_reversed' => true,
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

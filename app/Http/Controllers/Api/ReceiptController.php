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

        $receipt = Receipt::create(
            $request->validated()
        );

        return new ReceiptResource(
            $receipt->load([
                'tank',
                'product',
                'tanker',
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

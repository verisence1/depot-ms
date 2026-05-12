<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTankerRequest;
use App\Http\Requests\UpdateTankerRequest;
use App\Http\Resources\TankerResource;
use App\Models\Tanker;

class TankerController extends Controller
{
    public function index()
    {
        return TankerResource::collection(
            Tanker::with([
                'operator',
                'dispatches.customer',
                'dispatches.tank',
                'dispatches.product',
            ])
                ->latest()
                ->paginate(10)
        );
    }

    public function store(StoreTankerRequest $request)
    {
        $tanker = Tanker::create(
            $request->validated()
        );

        return new TankerResource(
            $tanker->load([
                'operator',
                'dispatches.customer',
                'dispatches.tank',
                'dispatches.product',
            ])
        );
    }

    public function show(Tanker $tanker)
    {
        return new TankerResource(
            $tanker->load([
                'operator',
                'dispatches.customer',
                'dispatches.tank',
                'dispatches.product',
            ])
        );
    }

    public function update(
        UpdateTankerRequest $request,
        Tanker $tanker
    ) {
        $tanker->update(
            $request->validated()
        );

        return new TankerResource(
            $tanker->fresh()->load([
                'operator',
                'dispatches.customer',
                'dispatches.tank',
                'dispatches.product',
            ])
        );
    }

    public function destroy(Tanker $tanker)
    {
        $tanker->delete();

        return response()->json([
            'message' => 'Tanker deleted successfully.',
        ]);
    }
}

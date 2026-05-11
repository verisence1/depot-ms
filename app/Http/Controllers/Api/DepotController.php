<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DepotResource;
use App\Models\Depot;
use Illuminate\Http\Request;

class DepotController extends Controller
{
    public function index()
    {
        return DepotResource::collection(
            Depot::withCount('tanks')
                ->latest()
                ->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string'],
            'location' => ['required', 'string'],
            'license_number' => [
                'required',
                'string',
                'unique:depots,license_number',
            ],
            'status' => ['required', 'string'],
        ]);

        $depot = Depot::create($validated);

        return new DepotResource($depot);
    }

    public function show(Depot $depot)
    {
        return new DepotResource(
            $depot->load([
                'tanks.product',
            ])
        );
    }

    public function update(
        Request $request,
        Depot $depot
    ) {

        $validated = $request->validate([
            'name' => ['string'],
            'location' => ['string'],
            'license_number' => [
                'string',
                'unique:depots,license_number,' . $depot->id,
            ],
            'status' => ['string'],
        ]);

        $depot->update($validated);

        return new DepotResource($depot);
    }

    public function destroy(Depot $depot)
    {
        $depot->delete();

        return response()->json([
            'message' => 'Depot deleted successfully.',
        ]);
    }
}

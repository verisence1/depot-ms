<?php

namespace App\Http\Controllers\Api;

use App\Models\Customer;
use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Dispatch;

class CustomerController extends Controller
{
    public function index()
    {
        return CustomerResource::collection(
            Customer::with([
                'dispatches.tank',
                'dispatches.product',
                'dispatches.tanker',
            ])
                ->latest()
                ->paginate(10)
        );
    }

    public function store(
        StoreCustomerRequest $request
    ) {

        $customer = Customer::create(
            $request->validated()
        );

        return new CustomerResource(
            $customer
        );
    }

    public function show(Customer $customer)
    {
        return new CustomerResource(
            $customer->load([
                'dispatches.tank',
                'dispatches.product',
                'dispatches.tanker',
            ])
        );
    }

    public function update(
        UpdateCustomerRequest $request,
        Customer $customer
    ) {

        $customer->update(
            $request->validated()
        );

        return new CustomerResource(
            $customer->fresh()
        );
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return response()->json([
            'message' =>
                'Customer deleted successfully.'
        ]);
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDispatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'depot_id' => [
                'required',
                'exists:depots,id',
            ],

            'tank_id' => [
                'required',
                'exists:tanks,id',
            ],

            'product_id' => [
                'required',
                'exists:products,id',
            ],

            'tanker_id' => [
                'required',
                'exists:tankers,id',
            ],

            'customer_id' => [
                'required',
                'exists:customers,id',
            ],

            'volume' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'dispatch_date' => [
                'required',
                'date',
            ],

            'waybill_no' => [
                'required',
                'string',
                'unique:dispatches,waybill_no',
            ],
        ];
    }
}

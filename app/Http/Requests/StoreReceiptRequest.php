<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReceiptRequest extends FormRequest
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

            'volume' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'receipt_date' => [
                'required',
                'date',
            ],

            'batch_ref' => [
                'required',
                'string',
                'unique:receipts,batch_ref',
            ],
        ];
    }
}

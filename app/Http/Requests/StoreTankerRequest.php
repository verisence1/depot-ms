<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTankerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'registration' => [
                'required',
                'string',
                'max:255',
                'unique:tankers,registration',
            ],

            'type' => [
                'required',
                'string',
                'in:truck,vessel,pipeline',
            ],

            'capacity_litres' => [
                'required',
                'numeric',
                'min:1',
            ],

            'operator_id' => [
                'nullable',
                'exists:users,id',
            ],
        ];
    }
}

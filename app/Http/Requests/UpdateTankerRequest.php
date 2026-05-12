<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTankerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'registration' => [
                'sometimes',
                'string',
                'max:255',
                'unique:tankers,registration,' . $this->tanker->id,
            ],

            'type' => [
                'sometimes',
                'string',
                'in:truck,vessel,pipeline',
            ],

            'capacity_litres' => [
                'sometimes',
                'numeric',
                'min:1',
            ],

            'operator_id' => [
                'sometimes',
                'nullable',
                'exists:users,id',
            ],
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'sometimes',
                'string',
                'max:255',
            ],

            'tin' => [
                'sometimes',
                'string',
                'max:255',
                'unique:customers,tin,' . $this->customer->id,
            ],

            'contact_email' => [
                'sometimes',
                'email',
                'max:255',
            ],

            'credit_limit' => [
                'sometimes',
                'numeric',
                'min:0',
            ],
        ];
    }
}

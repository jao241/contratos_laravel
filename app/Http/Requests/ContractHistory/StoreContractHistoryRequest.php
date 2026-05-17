<?php

namespace App\Http\Requests\ContractHistory;

use Illuminate\Foundation\Http\FormRequest;

class StoreContractHistoryRequest
    extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'action' => [
                'required',
                'string',
                'max:255',
            ],

            'field' => [
                'nullable',
                'string',
                'max:255',
            ],

            'old_value' => [
                'nullable',
                'string',
            ],

            'new_value' => [
                'nullable',
                'string',
            ],
        ];
    }
}

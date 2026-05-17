<?php

namespace App\Http\Requests\ContractItem;

use Illuminate\Foundation\Http\FormRequest;

class StoreContractItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            '*.quantity' => [
                'required',
                'integer',
                'min:1',
            ],
            '*.unit_price' => [
                'required',
                'numeric',
                'min:0',
            ],
            '*.service_id' => [
                'required',
                'integer',
                'exists:services,id',
            ],
        ];
    }
}

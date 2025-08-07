<?php

namespace App\Http\Requests\Api\Order;

use App\Http\Requests\Api\ApiRequest;

class ItemAddRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'courseID' => ['nullable', 'integer', 'gt:0', 'required_without:productID'],
            'productID' => ['nullable', 'integer', 'gt:0', 'required_without:courseID'],
            'quantity' => ['nullable', 'integer', 'gt:0'],
        ];
    }
}

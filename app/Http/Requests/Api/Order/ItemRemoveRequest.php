<?php

namespace App\Http\Requests\Api\Order;

use App\Http\Requests\Api\ApiRequest;
use Illuminate\Validation\Rule;

class ItemRemoveRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'courseID' => [
                'nullable',
                'integer',
                'gt:0',
                Rule::requiredIf(function () {
                    return $this->input('removeType') !== 'all' && !$this->has('productID');
                }),
            ],
            'productID' => [
                'nullable',
                'integer',
                'gt:0',
                Rule::requiredIf(function () {
                    return $this->input('removeType') !== 'all' && !$this->has('courseID');
                }),
            ],
            'removeType' => ['required', 'in:one,item,all'],
        ];
    }
}

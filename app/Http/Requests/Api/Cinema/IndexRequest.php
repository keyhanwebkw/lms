<?php

namespace App\Http\Requests\Api\Cinema;

use App\Http\Requests\Api\ApiRequest;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Config;
use Illuminate\Validation\Rule;

class IndexRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'categorySlug' => ['nullable', 'string'],
            'page' => ['nullable', 'integer', 'min:1'],
            'itemsPerPage' => [
                'nullable',
                'integer',
                'min:1',
                Rule::in(Config::get('subsystem.availableItemsPerPage'))
            ],
        ];
    }
}

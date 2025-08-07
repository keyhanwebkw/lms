<?php

namespace App\Http\Requests\Api\Course;

use App\Http\Requests\Api\ApiRequest;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Config;
use Illuminate\Validation\Rule;

class ListRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string', 'min:3', 'max:191'],
            'slug' => ['nullable', 'string', 'max:255'],
            'categorySlug' => ['nullable', 'string', 'max:255'],
            'categoryID' => ['nullable', 'integer'],
            'sort' => ['nullable', 'array'],
            'sort.*' => ['required', 'string', Rule::in(['asc', 'desc'])],
            'sortKey.*' => [
                'nullable',
                'string',
                Rule::in([
                    'name',
                    'duration',
                    'price',
                    'discountAmount',
                    'participants',
                    'score',
                    'name',
                    'startDate',
                    'endDate',
                    'created'
                ])
            ],
            'page' => ['nullable', 'integer', 'gt:0'],
            'itemsPerPage' => ['nullable', 'integer', 'gt:0', Rule::in(Config::get('subsystem.availableItemsPerPage'))],
        ];
    }

    protected function prepareForValidation(): void
    {
        $sort = array_merge($this->sort ?? [], ['created' => 'desc']);
        $this->merge([
            'sort' => $sort,
            'sortKey' => array_keys($sort),
        ]);
    }
}

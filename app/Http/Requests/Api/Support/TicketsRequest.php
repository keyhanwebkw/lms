<?php

namespace App\Http\Requests\Api\Support;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Config;
use App\Http\Requests\Api\ApiRequest;

class TicketsRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'orderBy' => [Rule::in(['ID', 'updated'])],
            'order' => [Rule::in('asc', 'desc')],
            'showPerPage' => [Rule::in(Config::get('api.list.limitRange'))],
            'page' => ['gt:0'],
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'page' => (int) ($this->page ?: 1),
            'showPerPage' => (int) ($this->showPerPage ?: Config::get('api.list.defaultLimit')),
            'orderBy' =>  strip_tags($this->orderBy) ?: 'updated',
            'order' =>  strip_tags($this->order) ?: 'desc',
        ]);
    }
}

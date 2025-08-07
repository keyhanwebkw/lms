<?php

namespace App\Http\Requests\Admin\Setting;

use Illuminate\Contracts\Validation\ValidationRule;
use Keyhanweb\Subsystem\Http\Requests\Admin\WebRequest;

class LatestArticlesRequest extends WebRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'homeLatestArticles' => ['required', 'array'],
            'homeLatestArticles.*' => ['sometimes', 'required', 'integer', 'exists:articles,ID'],
        ];
    }

    public function prepareForValidation(){
        $data = [];

        foreach ($this->homeLatestArticles as $value) {
            empty($value) ?: $data['homeLatestArticles'][] = $value;
        }

        if (empty($data)) {
            $data['homeLatestArticles'] = [];
        }

        $this->merge($data);
    }

    public function attributes(): array
    {
        $attributes = [
            'homeLatestArticles' => st('homeLatestArticles'),
        ];

        foreach ($this->homeLatestArticles as $index => $item) {
            $number = $index + 1;
            $attributes['homeLatestArticles' . $index] = st('Segment' , ['number' => $number]);
        }

        return $attributes;
    }
}

<?php

namespace App\Http\Requests\Admin\Setting;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Config;
use Keyhanweb\Subsystem\Http\Requests\Admin\WebRequest;

class QuickAccessesRequest extends WebRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'quickAccesses' => ['required', 'array'],
        ];

        foreach ($this->quickAccesses as $index => $item) {
            $rules['quickAccesses.' . $index] = ['sometimes', 'array', 'min:4'];
            $rules["quickAccesses.{$index}.color"] = ['sometimes', 'required', 'string'];
            $rules["quickAccesses.{$index}.title"] = ['sometimes', 'required', 'string'];
            $rules["quickAccesses.{$index}.link"] = ['sometimes', 'required', 'url'];
            $rules["quickAccesses.{$index}.iconSID"] = ['sometimes', 'required', 'string'];
            $rules["quickAccesses.{$index}.icon"] = array_merge(['sometimes', 'nullable', 'image'],
                Config::get('subsystem.storage.image.validate'));
        }

        return $rules;
    }

    public function prepareForValidation()
    {
        $data = [];
        foreach ($this->quickAccesses as $index => $item)
        {
            if ($item['color'] !== '#ffffff') {
                empty(array_filter($item)) ?: $data['quickAccesses'][$index] = array_filter($item);
            }
        }

        if (empty($data)) {
            $data['quickAccesses'] = [];
        }

        $this->merge($data);
    }

    public function attributes(): array
    {
        $attributes = [
            'quickAccesses' => st('homeQuickAccesses'),
        ];

        foreach ($this->quickAccesses as $index => $item) {
            $number = $index + 1;
            $attributes['quickAccesses.' . $index] = st('Segment' , ['number' => $number]);
            $attributes["quickAccesses.{$index}.link"] = st('Link') . ' ' . st('Segment' , ['number' => $number]);
            $attributes["quickAccesses.{$index}.title"] = st('Title') . ' ' . st('Segment' , ['number' => $number]);
            $attributes["quickAccesses.{$index}.color"] = st('Color') . ' ' . st('Segment' , ['number' => $number]);
            $attributes["quickAccesses.{$index}.iconSID"] = st('Icon sid') . ' ' . st('Segment' , ['number' => $number]);
            $attributes["quickAccesses.{$index}.icon"] = st('Icon') . ' ' . st('Segment' , ['number' => $number]);
        }

        return $attributes;
    }
}

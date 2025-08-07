<?php

namespace App\Http\Requests\Admin\Setting;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Config;
use Keyhanweb\Subsystem\Http\Requests\Admin\WebRequest;

class HomeBannersRequest extends WebRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'homeBanners' => ['required', 'array'],
        ];

        foreach ($this->homeBanners as $index => $banner) {
            $rules['homeBanners.' . $index] = ['sometimes', 'array', 'min:4'];
            $rules["homeBanners.{$index}.link"] = ['sometimes', 'required', 'url'];
            $rules["homeBanners.{$index}.startDisplayDate"] = ['sometimes', 'required', 'string'];
            $rules["homeBanners.{$index}.endDisplayDate"] = ['sometimes', 'required', 'string'];
            $rules["homeBanners.{$index}.bannerSID"] = ['sometimes', 'required', 'string'];
            $rules["homeBanners.{$index}.banner"] = array_merge(['sometimes', 'nullable', 'image'],
                Config::get('subsystem.storage.image.validate'));
        }

        return $rules;
    }

    public function prepareForValidation()
    {
        $data = [];

        foreach ($this->homeBanners as $index => $banner)
        {
            empty(array_filter($banner)) ?: $data['homeBanners'][$index] = array_filter($banner);
        }

        if (empty($data)) {
            $data['homeBanners'] = [];
        }

        $this->merge($data);
    }

    public function attributes(): array
    {
        $attributes = [
            'homeBanners' => st('homeBanners'),
        ];

        foreach ($this->homeBanners as $index => $banner) {
            $number = $index + 1;
            $attributes['homeBanners.' . $index] = st('Segment' , ['number' => $number]);
            $attributes["homeBanners.{$index}.link"] = st('Link') . ' ' . st('Segment' , ['number' => $number]);
            $attributes["homeBanners.{$index}.startDisplayDate"] = st('startDisplayDate') . ' ' . st('Segment' , ['number' => $number]);
            $attributes["homeBanners.{$index}.endDisplayDate"] = st('endDisplayDate') . ' ' . st('Segment' , ['number' => $number]);
            $attributes["homeBanners.{$index}.bannerSID"] = st('Banner sid') . ' ' . st('Segment' , ['number' => $number]);
            $attributes["homeBanners.{$index}.banner"] = st('banner') . ' ' . st('Segment' , ['number' => $number]);
        }

        return $attributes;
    }
}

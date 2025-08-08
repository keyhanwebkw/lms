<?php

namespace App\Http\Requests\Admin\Setting;

use Illuminate\Support\Facades\Config;
use Keyhanweb\Subsystem\Http\Requests\Admin\WebRequest;

class HomePageContentRequest extends WebRequest
{
    /**
     * قوانین اعتبارسنجی
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'firstImage' => array_merge(['nullable', 'image'], Config::get('subsystem.storage.image.validate')),
            'firstImageSID' => ['nullable', 'string'],

            'description' => ['required', 'string'],

            'sideImage' => array_merge(['nullable', 'image'], Config::get('subsystem.storage.image.validate')),
            'sideImageSID' => ['nullable', 'string'],

            'secondTitle' => ['required', 'string', 'max:255'],

            'introVideo' => array_merge(['nullable',], Config::get('subsystem.storage.video.validate')),
            'introVideoSID' => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => st('Title'),
            'firstImage' => st('First Image'),
            'firstImageSID' => st('First Image'),

            'description' => st('Description'),

            'sideImage' => st('Side Image'),
            'sideImageSID' => st('Side Image'),

            'secondTitle' => st('Second Title'),

            'introVideo' => st('Intro Video'),
            'introVideoSID' => st('Intro Video'),
        ];
    }
}

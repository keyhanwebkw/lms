<?php

namespace App\Http\Requests\Admin\Setting;

use Keyhanweb\Subsystem\Http\Requests\Admin\WebRequest;

class LogoRequest extends WebRequest
{
    public function rules(): array
    {
        return [
            'logo' => [
                'required', 'image', 'mimes:png', 'max:200',
            ],
        ];
    }
}

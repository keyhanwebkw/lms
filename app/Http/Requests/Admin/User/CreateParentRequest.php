<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Config;
use Illuminate\Validation\Rule;
use Keyhanweb\Subsystem\Enums\Gender;
use Keyhanweb\Subsystem\Http\Requests\Admin\WebRequest;

class CreateParentRequest extends WebRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:191'],
            'family' => ['required', 'string', 'min:3', 'max:191'],
            'nationalCode' => [
                'nullable',
                'validNationalCode',
                Rule::unique('users', 'nationalCode')
            ],
            'mobile' => [
                'required',
                'string',
                'validMobile',
                Rule::unique('users', 'mobile'),
            ],
            'birthDate' => ['nullable', 'ageLimit:15,100'],
            'gender' => ['required', Rule::in(Gender::values())],
            'password' => ['required', 'string', 'min:8'],
            'roles' => ['required', 'array', 'min:1'],
            'picture' => array_merge(['nullable', 'image'], Config::get('subsystem.storage.image.validate')),
            'biography' => ['nullable', 'string', 'max:65530'],
            'socialMedia' => ['nullable', 'string', 'max:65530'],
            'extraInfo' => ['nullable', 'string', 'max:65530'],
        ];
    }

    /**
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'mobile' => normalizeMobile(ltrim($this->mobile, 0)),
            'birthDate' => $this->parseTimeStamp($this->birthDate),
        ]);
    }
}

<?php

namespace App\Http\Requests\Api\User;

use App\Http\Requests\Api\ApiRequest;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;
use Keyhanweb\Subsystem\Enums\Gender;
use Keyhanweb\Subsystem\Enums\UserStatus;


class UpdateProfileRequest extends ApiRequest
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
                Rule::unique('users', 'nationalCode')->ignore(auth()->id())
            ],
            'gender' => ['required', Rule::in(Gender::values())],
            'birthDate' => ['nullable', 'integer', 'ageLimit:15,100'],
            'avatarSID' => ['nullable', 'string'],
        ];
    }
    protected function prepareForValidation(): void
    {
        $this->merge([
            'mobile' => normalizeMobile(ltrim($this->mobile, 0)),
        ]);
    }
}

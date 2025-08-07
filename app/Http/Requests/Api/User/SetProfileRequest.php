<?php

namespace App\Http\Requests\Api\User;

use App\Http\Requests\Api\ApiRequest;
use Illuminate\Validation\Rule;
use Keyhanweb\Subsystem\Enums\Gender;

class SetProfileRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:191'],
            'family' => ['required', 'string', 'min:3', 'max:191'],
            'nationalCode' => ['nullable', 'validNationalCode'],
            'birthDate' => ['nullable', 'integer', 'ageLimit:15,100'],
            'gender' => ['required', Rule::in(Gender::values())],
            'avatarSID' => ['nullable', 'string'],
        ];
    }
}

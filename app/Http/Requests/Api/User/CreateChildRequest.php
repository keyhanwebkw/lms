<?php

namespace App\Http\Requests\Api\User;

use App\Http\Requests\Api\ApiRequest;
use Illuminate\Validation\Rule;
use Keyhanweb\Subsystem\Enums\Gender;

class CreateChildRequest extends ApiRequest
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
            'gender' => ['required', Rule::in(Gender::values())],
            'username' => ['nullable', 'string', 'min:3', 'max:32', 'englishOnly', 'unique:App\Models\User'],
            'nationalCode' => ['nullable', 'validNationalCode', 'unique:App\Models\User'],
            'birthDate' => ['nullable', 'integer', 'ageLimit:3,30'],
            'avatarSID' => ['required', 'string'],
        ];
    }

    /**
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => trim($this->name),
            'username' => str_replace(' ', '_', trim($this->username)),
        ]);
    }
}

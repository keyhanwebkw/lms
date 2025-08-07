<?php

namespace App\Http\Requests\Api\User;

use App\Http\Requests\Api\ApiRequest;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;
use Keyhanweb\Subsystem\Enums\Gender;

class UpdateChildRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $avatarSIDs = [
            'dA000000-0000-0000-0000-00000avatar1',
            'dA000000-0000-0000-0000-00000avatar2',
            'dA000000-0000-0000-0000-00000avatar3',
            'dA000000-0000-0000-0000-00000avatar4',
            'dA000000-0000-0000-0000-00000avatar5',
            'dA000000-0000-0000-0000-00000avatar6',
        ];

        return [
            'name' => ['required', 'string', 'min:3', 'max:191'],
            'gender' => ['required', Rule::in(Gender::values())],
            'childID' => ['required', 'integer', 'gt:0'],
            'username' => [
                'required',
                'string',
                'min:3',
                'max:32',
                'englishOnly',
                Rule::unique('users', 'username')->ignore($this->childID)
            ],
            'nationalCode' => [
                'nullable',
                'validNationalCode',
                Rule::unique('users', 'nationalCode')->ignore($this->childID)
            ],
            'birthDate' => ['nullable', 'integer', 'ageLimit:3,30'],
            'avatarSID' => ['required', 'string', Rule::in($avatarSIDs)],
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

<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;
use Keyhanweb\Subsystem\Enums\Gender;
use Keyhanweb\Subsystem\Enums\UserStatus;
use Keyhanweb\Subsystem\Http\Requests\Admin\WebRequest;

class UpdateChildRequest extends WebRequest
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
            'gender' => ['required', Rule::in(Gender::values())],
            'username' => [
                'required',
                'string',
                'min:3',
                'max:32',
                'englishOnly',
                Rule::unique('users', 'username')->ignore($this->user->ID)
            ],
            'nationalCode' => [
                'nullable',
                'validNationalCode',
                Rule::unique('users', 'nationalCode')->ignore($this->user->ID)
            ],
            'birthDate' => ['nullable', 'ageLimit:3,30'],
            'status' => ['required', Rule::in(UserStatus::values())],
            'parentID' => ['required', 'string'],
        ];
    }

    /**
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'username' => str_replace(' ', '_', $this->username),
            'birthDate' => $this->parseTimeStamp($this->birthDate),
        ]);
    }
}

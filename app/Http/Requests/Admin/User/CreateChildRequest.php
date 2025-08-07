<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;
use Keyhanweb\Subsystem\Enums\Gender;
use Keyhanweb\Subsystem\Http\Requests\Admin\WebRequest;

class CreateChildRequest extends WebRequest
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
                'nullable',
                'string',
                'min:3',
                'max:32',
                'englishOnly',
                Rule::unique('users', 'username')
            ],
            'nationalCode' => [
                'nullable',
                'validNationalCode',
                Rule::unique('users', 'nationalCode')
            ],
            'birthDate' => ['nullable', 'ageLimit:3,30'],
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

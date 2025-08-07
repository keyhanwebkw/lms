<?php

namespace App\Http\Requests\Admin\Assignment\Check;

use App\Enums\UserAssignmentStatuses;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;
use Keyhanweb\Subsystem\Http\Requests\Admin\WebRequest;

class UpdateRequest extends WebRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => ['required', 'string', Rule::in(UserAssignmentStatuses::values())],
            'managerResponse' => ['required', 'string', 'min:3', 'max:65530'],
            'receivedScore' => ['required', 'integer', 'min:0', 'max:1000000'],
        ];
    }
}

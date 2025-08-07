<?php

namespace App\Http\Requests\Api\Assignment;

use App\Http\Requests\Api\ApiRequest;
use App\Models\Assignment;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class ReceiveRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'assignmentID' => ['required', 'integer', 'min:0'],
        ];
    }
}

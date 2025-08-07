<?php

namespace App\Http\Requests\Api\Exam;

use App\Http\Requests\Api\ApiRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class AnswerRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'questionID' => ['required', 'integer', 'gt:0'],
            'userAnswerID' => ['required', 'integer', 'gt:0'],
            'optionID' => ['nullable', 'integer', 'gt:0'],
        ];
    }
}

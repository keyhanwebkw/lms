<?php

namespace App\Http\Requests\Api\Exam;

use App\Http\Requests\Api\ApiRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class ResultRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'userAnswerID' => ['required_without:examID', 'integer', 'gt:0'],
            'examID' => ['required_without:userAnswerID', 'integer', 'gt:0'],
        ];
    }
}

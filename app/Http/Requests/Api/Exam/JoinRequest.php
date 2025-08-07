<?php

namespace App\Http\Requests\Api\Exam;

use App\Http\Requests\Api\ApiRequest;
use App\Models\Exam;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class JoinRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'examID' => ['required', 'integer', 'gt:0'],
        ];
    }
}

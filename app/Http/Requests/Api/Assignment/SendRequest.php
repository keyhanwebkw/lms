<?php

namespace App\Http\Requests\Api\Assignment;

use App\Http\Requests\Api\ApiRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class SendRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'userAssignmentID' => ['required', 'integer', 'min:0'],
            'content' => ['required_without:text', 'array'],
            'content.*' => ['required', 'file'],
            'text' => ['required_without:content', 'string', 'min:1', 'max:65530'],
        ];
    }
}

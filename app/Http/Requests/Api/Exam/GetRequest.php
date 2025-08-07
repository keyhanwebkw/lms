<?php

namespace App\Http\Requests\Api\Exam;

use App\Http\Requests\Api\ApiRequest;
use App\Models\Exam;
use Illuminate\Validation\Rule;

class GetRequest extends ApiRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'examID' => ['required', 'integer', 'gt:0'],
        ];
    }
}

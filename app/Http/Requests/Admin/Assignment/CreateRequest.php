<?php

namespace App\Http\Requests\Admin\Assignment;

use Illuminate\Contracts\Validation\ValidationRule;
use Keyhanweb\Subsystem\Http\Requests\Admin\WebRequest;

class CreateRequest extends WebRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:3', 'max:255'],
            'description' => ['nullable', 'string', 'min:3', 'max:65530'],
            'deadline' => ['nullable', 'integer', 'min:0', 'max:1440'],
            'minScoreToPass' => ['required', 'integer', 'min:0', 'max:1000000'],
            'retryCount' => ['required', 'integer', 'min:1', 'max:1000000'],
            'content' => ['nullable', 'file'],
        ];
    }
}

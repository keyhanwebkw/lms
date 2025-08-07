<?php

namespace App\Http\Requests\Admin\EpisodeContent;

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
            'title' => ['required', 'string', 'max:255'],
            'duration' => ['required', 'string'],
            'description' => ['nullable', 'string', 'max:65530'],
            'contentSID' => ['required', 'string', 'max:255'],
            'returnUrl' => ['required', 'string'],
        ];
    }
}

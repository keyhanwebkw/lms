<?php

namespace App\Http\Requests\Admin\Comment;

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
            'content' => 'required|string|max:1024',
        ];
    }
}

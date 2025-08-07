<?php

namespace App\Http\Requests\Admin\Comment;

use App\Enums\CommentStatuses;
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
            'content' => ['required', 'string', 'max:1024'],
            'status' => ['required', 'string', Rule::in(CommentStatuses::values())],
        ];
    }
}

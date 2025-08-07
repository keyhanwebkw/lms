<?php

namespace App\Http\Requests\Api\Comment;

use App\Http\Requests\Api\ApiRequest;
use App\Models\Comment;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

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
            'commentableType' => ['required', 'string', Rule::in(Comment::COMMENTABLE_TYPES)],
            'commentableID' => ['required', 'integer'],
            'parentID' => ['nullable', 'integer'],
            'content' => ['required', 'string', 'max:1024'],
        ];
    }
}

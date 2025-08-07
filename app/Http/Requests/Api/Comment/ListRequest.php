<?php

namespace App\Http\Requests\Api\Comment;

use App\Http\Requests\Api\ApiRequest;
use App\Models\Comment;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Config;
use Illuminate\Validation\Rule;

class ListRequest extends ApiRequest
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
            'commentableID' => ['required' , 'integer'],
            'page' => ['nullable', 'integer', 'gt:0'],
            'itemsPerPage' => ['nullable', 'integer', 'gt:0', Rule::in(Config::get('subsystem.availableItemsPerPage'))],
        ];
    }
}

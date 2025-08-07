<?php

namespace App\Http\Requests\Admin\ChildGame\MovieCategory;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Config;
use Illuminate\Validation\Rule;
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
            'title' => ['required', 'string', 'min:1', 'max:255'],
            'slug' => [
                'required',
                'string',
                'min:1',
                'max:255',
                'englishOnly',
                Rule::unique('cg_moviesCategories', 'slug')
            ],
            'sortOrder' => [
                'required',
                'integer',
                'min:1',
                'max:32000',
                Rule::unique('cg_moviesCategories', 'sortOrder')
            ],
            'description' => ['nullable', 'string', 'min:1', 'max:65530'],
            'photo' => array_merge(['required', 'image'], Config::get('subsystem.storage.image.validate')),
        ];
    }
}

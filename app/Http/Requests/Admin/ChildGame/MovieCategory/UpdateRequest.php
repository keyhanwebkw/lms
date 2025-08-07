<?php

namespace App\Http\Requests\Admin\ChildGame\MovieCategory;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Config;
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
            'title' => ['required', 'string', 'min:1', 'max:255'],
            'slug' => [
                'required',
                'string',
                'min:1',
                'max:255',
                'englishOnly',
                Rule::unique('cg_moviesCategories', 'slug')
                    ->ignore($this->movieCategory->ID)
            ],
            'sortOrder' => [
                'required',
                'integer',
                'min:1',
                'max:32000',
                Rule::unique('cg_moviesCategories', 'sortOrder')
                    ->ignore($this->movieCategory->ID)
            ],
            'description' => ['nullable', 'string', 'min:1', 'max:65530'],
            'photo' => array_merge(['nullable', 'image'], Config::get('subsystem.storage.image.validate')),
        ];
    }
}

<?php

namespace App\Http\Requests\Admin\ChildGame\Movie;

use App\Enums\MovieTypes;
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
            'name' => ['required', 'string', 'min:1', 'max:255'],
            'slug' => [
                'required',
                'string',
                'min:1',
                'max:255',
                'englishOnly',
                Rule::unique('cg_movies', 'slug')
            ],
            'type' => ['required', 'string', Rule::in(MovieTypes::values())],
            'description' => ['nullable', 'string', 'min:1', 'max:65530'],
            'poster' => array_merge(['required', 'image'], Config::get('subsystem.storage.image.validate')),
            'movieCategories' => ['required', 'array', 'min:1'],
        ];
    }
}

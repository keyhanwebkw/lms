<?php

namespace App\Http\Requests\Admin\CourseCategory;

use App\Enums\CourseCategoryStatuses;
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
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('coursesCategories', 'title')->ignore($this->courseCategory->ID)
            ],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('coursesCategories', 'slug')->ignore($this->courseCategory->ID),
                'english_only'
            ],
            'description' => ['nullable', 'string', 'max:65530'],
            'sortOrder' => [
                'required',
                'integer',
                'min:1',
                'max:32000',
                Rule::unique('coursesCategories', 'sortOrder')->ignore($this->courseCategory->ID)
            ],
            'photo' => array_merge(['nullable', 'image'], Config::get('subsystem.storage.image.validate')),
            'metaTitle' => ['nullable', 'string', 'max:255'],
            'metaDescription' => ['nullable', 'string', 'max:65530'],
            'metaKeyword' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(CourseCategoryStatuses::values())],
        ];
    }
}

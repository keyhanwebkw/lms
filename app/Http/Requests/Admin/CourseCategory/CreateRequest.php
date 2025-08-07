<?php

namespace App\Http\Requests\Admin\CourseCategory;

use App\Enums\CourseCategoryStatuses;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Config;
use Illuminate\Validation\Rule;
use Keyhanweb\Subsystem\Http\Requests\Admin\WebRequest;

class CreateRequest extends WebRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255', 'unique:coursesCategories,title'],
            'slug' => ['required', 'string', 'max:255', 'unique:coursesCategories,slug', 'english_only'],
            'description' => ['nullable', 'string', 'max:65530'],
            'sortOrder' => ['required', 'integer', 'min:1', 'max:32000'],
            'photo' => array_merge(['required', 'image'], Config::get('subsystem.storage.image.validate')),
            // حداکثر 2MB
            'metaTitle' => ['nullable', 'string', 'max:255'],
            'metaDescription' => ['nullable', 'string', 'max:65530'],
            'metaKeyword' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(CourseCategoryStatuses::values())],
        ];
    }
}

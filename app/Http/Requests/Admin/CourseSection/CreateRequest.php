<?php

namespace App\Http\Requests\Admin\CourseSection;

use Illuminate\Contracts\Validation\ValidationRule;
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
            'title' => ['required', 'string', 'max:255'],
            'sortOrder' => [
                'required',
                'integer',
                'min:1',
                'max:32000',
                Rule::unique('courseSections')->where(function ($query) {
                    return $query->where('courseID', request()->course->ID);
                }),
            ],
        ];
    }
}

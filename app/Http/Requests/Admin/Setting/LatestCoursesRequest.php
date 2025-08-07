<?php

namespace App\Http\Requests\Admin\Setting;

use Illuminate\Contracts\Validation\ValidationRule;
use Keyhanweb\Subsystem\Http\Requests\Admin\WebRequest;

class LatestCoursesRequest extends WebRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'homeLatestCourses' => ['required', 'array'],
            'homeLatestCourses.*' => ['sometimes', 'required', 'integer', 'exists:courses,ID'],
        ];
    }

    public function prepareForValidation(){
        $data = [];

        foreach ($this->homeLatestCourses as $value) {
            empty($value) ?: $data['homeLatestCourses'][] = $value;
        }

        if (empty($data)) {
            $data['homeLatestCourses'] = [];
        }

        $this->merge($data);
    }

    public function attributes(): array
    {
        $attributes = [
            'homeLatestCourses' => st('homeLatestCourses'),
        ];

        foreach ($this->homeLatestCourses as $index => $item) {
            $number = $index + 1;
            $attributes['homeLatestCourses.' . $index] = st('Segment' , ['number' => $number]);
        }

        return $attributes;
    }
}

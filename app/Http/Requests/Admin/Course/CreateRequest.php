<?php

namespace App\Http\Requests\Admin\Course;

use App\Enums\CourseCategoryStatuses;
use App\Enums\CourseLevels;
use App\Enums\CourseStatuses;
use App\Enums\CourseTypes;
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
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:courses,slug', 'english_only'],
            'duration' => ['required', 'integer', 'min:1', 'max:86400'], // 10 Year
            'type' => ['required', Rule::in(CourseTypes::values())],
            'price' => ['nullable', 'numeric', 'min:0', 'max:200000000000'], // 200'000'000'000
            'discountAmount' => ['nullable', 'numeric', 'min:0', 'lte:price'],
            'participantLimitation' => ['nullable', 'integer', 'min:0', 'max:100000000'], //100'000'000
            'status' => ['required', Rule::in(CourseStatuses::values())],
            'level' => ['required', Rule::in(CourseLevels::values())],
            'startDate' => ['required', 'integer'],
            'endDate' => ['nullable', 'integer', 'after:startDate'],
            'description' => ['required', 'string', 'max:65530'],
            'courseCategories' => ['required', 'array', 'min:1'],
            'banner' => ['nullable', 'mimes:png,jpg,jpeg,webp'],
            'introVideo' => ['nullable', 'mimes:mp4,avi,mpeg,mov'],
            'teacherID' => ['required', 'integer', Rule::exists('users', 'ID')],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'price' => $this->parseAmount($this->price) ?? 0,
            'discountAmount' => $this->parseAmount($this->discountAmount) ?? 0,
            'startDate' => $this->parseTimeStamp($this->startDate),
            'endDate' => $this->parseTimeStamp($this->endDate),
        ]);
    }
}

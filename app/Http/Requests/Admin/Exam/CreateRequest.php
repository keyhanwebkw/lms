<?php

namespace App\Http\Requests\Admin\Exam;

use App\Enums\ExamStatuses;
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
            'description' => ['required', 'string', 'max:65530'],
            'startDate' => ['required', 'integer'],
            'endDate' => ['required', 'integer', 'after:startDate'],
            'duration' => ['required', 'integer', 'min:0', 'max:1440'],
            'minScoreToPass' => ['required', 'integer', 'min:0', 'max:1000'],
            'retryAttempts' => ['required', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'startDate' => $this->parseTimeStamp($this->startDate),
            'endDate' => $this->parseTimeStamp($this->endDate),
        ]);
    }
}

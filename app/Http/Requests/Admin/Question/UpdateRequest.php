<?php

namespace App\Http\Requests\Admin\Question;

use App\Enums\questionDifficultyLevel;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Config;
use Illuminate\Validation\Rule;
use Keyhanweb\Subsystem\Http\Requests\Admin\WebRequest;

class UpdateRequest extends WebRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        if (!empty($this->answers)) $arrayKeys = array_keys($this->answers);
        return [
            'questionDifficultyLevel' => ['required', Rule::in(questionDifficultyLevel::values())],
            'timeLimit' => ['required', 'integer', 'min:1', 'max:600'],
            'score' => ['required', 'integer', 'min:1', 'max:100'],
            'sortOrder' => ['required', 'integer', 'min:1'],
            'content' => array_merge(['nullable', 'image'], Config::get('subsystem.storage.image.validate')),
            'question' => ['required', 'string', 'max:500'],
            'answers' => ['required', 'array', 'min:2', 'max:10'],
            'answers.*' => ['required', 'string', 'max:255'],
            'correct' => ['required', 'integer', $this->answers ? Rule::in($arrayKeys) : ''],
        ];
    }
}

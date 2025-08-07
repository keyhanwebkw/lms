<?php

namespace App\Http\Requests\Admin\ChildGame\SeasonEpisode;

use Illuminate\Contracts\Validation\ValidationRule;
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
            'title' => ['required_without:movieID', 'string', 'max:255'],
            'videoSID' => ['required_without:videoUrl', 'string', 'max:255'],
            'videoUrl' => ['required_without:videoSID', 'string', 'max:255', 'url'],
            'sortOrder' => ['required_without:movieID', 'integer'],
            'seasonID' => ['required_without:movieID', 'integer'],
            'movieID' => ['required_without:seasonID', Rule::unique('cg_seasonEpisodes', 'movieID')],
            'returnUrl' => ['required', 'string'],
        ];
    }

    public function prepareForValidation()
    {
        $data = [];

        foreach ($this->all() as $field => $value) {
            !$value ?: $data[$field] = $value;
        }

        $this->replace($data);
    }
}

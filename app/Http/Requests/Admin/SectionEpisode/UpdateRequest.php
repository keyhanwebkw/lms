<?php

namespace App\Http\Requests\Admin\SectionEpisode;

use App\Enums\EpisodeStatuses;
use App\Models\SectionEpisode;
use Illuminate\Contracts\Validation\ValidationRule;
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
            'sortOrder' => [
                'required',
                'integer',
                'min:1',
                'max:32000',
                Rule::unique('sectionEpisodes')->where(function ($query) {
                    $query->where('courseSectionID', $this->sectionEpisode->courseSectionID)
                        ->where('sortOrder', $this->sortOrder);
                })->ignore($this->sectionEpisode->ID)
            ],
            'isMandatory' => [
                'nullable',
                'boolean',
            ],
            'status' => [
                'required',
                'string',
                Rule::in(EpisodeStatuses::values()),
            ]
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Keyhanweb\Subsystem\Http\Resources\StorageResource;

class QuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * @link https://docs.google.com/document/d/1HE6JHRpQxBwEg9KeRoYfM-vB1KnmV1hBaiA7620l_oY/edit?tab=t.0
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'ID' => $this->ID,
            'question' => $this->question,
            'difficultyLevel' => $this->questionDifficultyLevel,
            'timeLimit' => $this->timeLimit,
            'score' => $this->score,
            'content' => !empty($this->contentSID) ? StorageResource::make($this->storage) : null,
            'options' => QuestionOptionsResource::collection($this->options),
        ];
    }
}

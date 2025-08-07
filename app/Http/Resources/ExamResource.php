<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Keyhanweb\Subsystem\Http\Resources\StorageResource;

class ExamResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'ID' => $this->ID,
            'title' => $this->title,
            'description' => $this->description,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'score' => $this->score,
            'duration' => $this->duration,
            'minScoreToPass' => $this->minScoreToPass,
            'retryAttempts' => $this->retryAttempts,
            'questionsCount' => $this->questionsCount
        ];
    }
}

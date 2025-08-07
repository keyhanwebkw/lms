<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamEpisodeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @link https://docs.google.com/document/d/1xgiM4ddEBZzshpdgA18b1ul2MjjMdMMtDhdzO8_1wV0/edit?tab=t.0
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'ID' => $this->ID,
            'type' => 'exam',
            'title' => $this->title,
            'questionCount' => $this->questionCount,
            'score' => $this->score,
            'permission' => $this->permission,
            'isDone' => $this->isDone,
            'courseSectionID' => $this->courseSectionID,
        ];
    }
}

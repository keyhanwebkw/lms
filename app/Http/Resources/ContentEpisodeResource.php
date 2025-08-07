<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContentEpisodeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     * @link https://docs.google.com/document/d/1xgiM4ddEBZzshpdgA18b1ul2MjjMdMMtDhdzO8_1wV0/edit?tab=t.0
     */
    public function toArray(Request $request): array
    {
        return [
            'ID' => $this->ID,
            'type' => 'content',
            'title' => $this->title,
            'duration' => $this->duration,
            'permission' => $this->permission,
            'isDone' => $this->isDone,
            'courseSectionID' => $this->courseSectionID,
        ];
    }
}

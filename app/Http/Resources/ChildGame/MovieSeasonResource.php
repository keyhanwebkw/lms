<?php

namespace App\Http\Resources\ChildGame;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieSeasonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     * @link https://docs.google.com/document/d/1hOGM1dizOthtHSYDsmHMz7Lqb5uf4XCe8LH0ttsoQRI/edit?tab=t.0
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'episodes' => SeasonEpisodeResource::collection($this->episodes)
        ];
    }
}

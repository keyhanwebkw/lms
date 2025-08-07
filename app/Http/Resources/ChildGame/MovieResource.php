<?php

namespace App\Http\Resources\ChildGame;

use App\Enums\MovieTypes;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     * @link https://docs.google.com/document/d/1qsBjr9zjmM8x6wrulnKAKNf7vDLjumzIOztPwnHj560/edit?tab=t.0
     */
    public function toArray(Request $request): array
    {
        $resource =  [
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'type' => $this->type,
        ];

        match ($this->type){
            MovieTypes::Series->value => $resource['seasons'] = MovieSeasonResource::collection($this->seasons),
            MovieTypes::Film->value => $resource['content'] = SeasonEpisodeResource::make($this->content),
        };

        return $resource;
    }
}

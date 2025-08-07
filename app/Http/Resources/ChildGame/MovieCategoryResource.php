<?php

namespace App\Http\Resources\ChildGame;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Keyhanweb\Subsystem\Http\Resources\StorageResource;

class MovieCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     * @link https://docs.google.com/document/d/1cYt_IURpkhmRRrRcMct_q6f6K80vbY68H30ui6Qesv8/edit?tab=t.0
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'photo' => StorageResource::make($this->storage),
            'movies' => MovieResource::collection($this->movies),
        ];
    }
}

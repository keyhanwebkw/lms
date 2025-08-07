<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Keyhanweb\Subsystem\Http\Resources\StorageResource;

class EpisodeContentResource extends JsonResource
{
    /**
     * @link https://docs.google.com/document/d/1_ufw0tiiRX8dUnCqGiN9gg1R6GX0CujUSCNM8py4Nuk/edit?usp=sharing
     *
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'ID' => $this->ID,
            'title' => $this->title,
            'duration' => $this->duration,
            'description' => $this->description,
            'content' => StorageResource::make($this->storage),
        ];
    }
}

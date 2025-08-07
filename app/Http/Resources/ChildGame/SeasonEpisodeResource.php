<?php

namespace App\Http\Resources\ChildGame;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Keyhanweb\Subsystem\Http\Resources\StorageResource;

class SeasonEpisodeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     * @link  https://docs.google.com/document/d/1RcgqBaYLWzHmXDQgOPicVTmMyOK0L1dK7thuOR2o_h8/edit?tab=t.0
     */
    public function toArray(Request $request): array
    {
        $resource = [
            'title' => $this->title,
            'videoUrl' => $this->videoUrl,
            'video' => StorageResource::make($this->storage)
        ];

        if (empty($this->title)) {
            unset($resource['title']);
        }

        return $resource;
    }
}

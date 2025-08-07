<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Keyhanweb\Subsystem\Http\Resources\StorageResource;

class AssignmentReceiveResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     * @link https://docs.google.com/document/d/1WRLjh18miCWVL6FJwzP1J6mCq7AK5t8JGX8Nk7bNNBc/edit?tab=t.0
     */
    public function toArray(Request $request): array
    {
        return [
            'ID' => $this->ID,
            'title' => $this->title,
            'description' => $this->description,
            'content' => $this->contentSID ? StorageResource::make($this->storage) : null,
        ];
    }
}

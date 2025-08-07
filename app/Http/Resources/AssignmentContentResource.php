<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Keyhanweb\Subsystem\Http\Resources\StorageResource;

class AssignmentContentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     * @link https://docs.google.com/document/d/1r0wFLDM450xD8hRu5J8RDZIiLNGgM7ryV-RTcWIY8PI/edit?tab=t.0
     */
    public function toArray(Request $request): array
    {
        return [
            'ID' => $this->ID,
            'text' => $this->text,
            'content' => $this->contentSID ? StorageResource::make($this->storage) : null,
        ];
    }
}

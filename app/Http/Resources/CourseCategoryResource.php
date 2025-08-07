<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Keyhanweb\Subsystem\Http\Resources\StorageResource;

class CourseCategoryResource extends JsonResource
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
            'slug' => $this->slug,
            'description' => $this->description,
            'photoSID' => StorageResource::make($this->storage),
        ];
    }
}

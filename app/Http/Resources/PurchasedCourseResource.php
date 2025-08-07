<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Keyhanweb\Subsystem\Http\Resources\StorageResource;

class PurchasedCourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     * @link https://docs.google.com/document/d/17QuC5EvXaNH3Qqy1Nj46CIn4LqJi74QOXc5ci3B9IaI/edit?tab=t.0
     */
    public function toArray(Request $request): array
    {
        return [
            'ID' => $this->ID,
            'name' => $this->name,
            'duration' => $this->duration,
            'type' => $this->type,
            'status' => $this->status,
            'slug' => $this->slug,
            'level' => $this->level,
            'teacher' => TeacherResource::make($this->teacher),
            'banner' => StorageResource::make($this->courseIntro?->storage),
        ];
    }
}

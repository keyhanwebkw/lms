<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseCategorySummaryResource extends JsonResource
{
    /**
     * @link https://docs.google.com/document/d/14ENM3di27r_eOHhEVBVzfB1fDqjh4EHIXIlnZjYlZVc/edit?tab=t.0
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
            'slug' => $this->slug,
        ];
    }
}

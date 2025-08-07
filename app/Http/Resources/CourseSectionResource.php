<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseSectionResource extends JsonResource
{
    /**
     * @link https://docs.google.com/document/d/1wIF-jTR4PNBhbpyT7QoblmAjzw0-m91wOJBKGNa9fSQ/edit?usp=sharing
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
        ];
    }
}

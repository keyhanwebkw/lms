<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Keyhanweb\Subsystem\Http\Resources\StorageResource;

class CourseOrderSummaryResource extends JsonResource
{
    /**
     * @link https://docs.google.com/document/d/1B4Z5wYxaJH6w1ShImZlz-y5-Z4laPF6a1ArO6FnV4Kk/edit?usp=sharing
     *
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'ID' => $this->ID,
            'name' => $this->name,
            'type' => $this->type,
            'duration' => $this->duration,
            'price' => $this->price,
            'discountAmount' => $this->discountAmount,
            'totalPrice' => $this->totalPrice,
            'status' => $this->status,
            'score' => $this->score,
            'slug' => $this->slug,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'banner' => $this->relationLoaded('courseIntro') && $this->courseIntro?->relationLoaded('storage')
                ? StorageResource::make($this->courseIntro->storage)
                : null,
            'teacher' => $this->relationLoaded('teacher')
                ? TeacherResource::make($this->teacher)
                : null,
            'category' => $this->relationLoaded('categories')
                ? CourseCategorySummaryResource::collection($this->categories)
                : [],
        ];
    }
}

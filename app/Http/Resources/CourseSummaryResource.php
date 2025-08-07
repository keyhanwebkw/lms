<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Keyhanweb\Subsystem\Http\Resources\StorageResource;

class CourseSummaryResource extends JsonResource
{
    /**
     * @link https://docs.google.com/document/d/1Z9UxDVf5n6_XmC-fj9EUeULd5Iz85qSo4CZSZ7yW2-E/edit?usp=sharing
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
            'description' => $this->description,
            'type' => $this->type,
            'duration' => $this->duration,
            'price' => $this->price,
            'discountAmount' => $this->discountAmount,
            'totalPrice' => $this->totalPrice,
            'status' => $this->status,
            'score' => $this->score,
            'teacherID' => $this->teacherID,
            'slug' => $this->slug,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'banner' => StorageResource::make($this->courseIntro?->storage),
            'teacher' => TeacherResource::make($this->teacher),
            'category' => CourseCategorySummaryResource::collection($this->categories)
        ];
    }
}

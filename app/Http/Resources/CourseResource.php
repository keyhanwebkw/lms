<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Keyhanweb\Subsystem\Http\Resources\StorageResource;

class CourseResource extends JsonResource
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
            'duration' => $this->duration,
            'type' => $this->type,
            'price' => $this->price,
            'discountAmount' => $this->discountAmount,
            'totalPrice' => $this->totalPrice,
            'participants' => $this->participants,
            'participantLimitation' => $this->participantLimitation,
            'status' => $this->status,
            'score' => $this->score,
            'slug' => $this->slug,
            'level' => $this->level,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'introVideo' => StorageResource::make($this->courseIntro?->storage),
            'teacher' => TeacherResource::make($this->teacher),
            'courseSection' => CourseSectionResource::collection($this->courseSection),
            'category' => CourseCategorySummaryResource::collection($this->categories)
        ];
    }
}

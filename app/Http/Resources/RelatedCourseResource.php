<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Keyhanweb\Subsystem\Http\Resources\StorageResource;

class RelatedCourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     * @link https://docs.google.com/document/d/138xki-h99QhGvIE1CaW5tt8VNFBDSwLj_q_EPqTp_74/edit?tab=t.0
     */
    public function toArray(Request $request): array
    {
        return [
            'ID' => $this->ID,
            'name' => $this->name,
            'slug' => $this->slug,
            'duration' => $this->duration,
            'price' => $this->price,
            'discountAmount' => $this->discountAmount,
            'totalPrice' => $this->totalPrice,
            'status' => $this->status,
            'banner' => StorageResource::make($this->courseIntro?->storage),
            'teacher' => TeacherResource::make($this->teacher),
        ];
    }
}

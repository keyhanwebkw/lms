<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Keyhanweb\Subsystem\Http\Resources\StorageResource;

class QuestionOptionsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * @link https://docs.google.com/document/d/1rbbnZu5fC6NdvdcqQB8Cp75J-lAaowy5LYZe85aiKJk/edit?tab=t.0
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'ID' => $this->ID,
            'value' => $this->answer,
            'content' => !empty($this->contentSID) ? StorageResource::make($this->storage) : null,
        ];
    }
}

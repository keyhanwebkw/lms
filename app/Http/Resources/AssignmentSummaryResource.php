<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssignmentSummaryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     * @link https://docs.google.com/document/d/1Woi6m5glWwCGweJU3khk2I4rY4ezX-4GYq6yI5pHflI/edit?tab=t.0
     */
    public function toArray(Request $request): array
    {
        return [
            'ID' => $this->ID,
            'title' => $this->title,
            'deadline'=> $this->deadline,
            'score' => $this->score,
            'minScoreToPass' => $this->minScoreToPass,
            'isRequired' => $this->isRequired,
            'retryCount' => $this->retryCount,
        ];
    }
}

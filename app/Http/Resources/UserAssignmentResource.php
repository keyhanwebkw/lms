<?php

namespace App\Http\Resources;

use App\Enums\UserAssignmentStatuses;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserAssignmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     * @link https://docs.google.com/document/d/1ph_-uSDfrhvFj4g10lbw-9SNMhlHrd921K4RQ5XHGro/edit?tab=t.0
     */
    public function toArray(Request $request): array
    {
        return [
            'ID' => $this->ID,
            'text' => $this->managerResponse,
            'receivedScore' => $this->receivedScore,
            'retryCount' => $this->retryCount,
            'isAccepted' => ($this->status == UserAssignmentStatuses::Accepted->value)
        ];
    }
}

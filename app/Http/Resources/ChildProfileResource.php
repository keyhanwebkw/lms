<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Keyhanweb\Subsystem\Http\Resources\StorageResource;

class ChildProfileResource extends JsonResource
{
    /**
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
            'username' => $this->username,
            'gender' => $this->gender,
            'avatarSID' => StorageResource::make($this->avatar),
        ];
    }
}

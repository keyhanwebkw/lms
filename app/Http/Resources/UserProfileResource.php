<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Keyhanweb\Subsystem\Http\Resources\StorageResource;

class UserProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * @link https://docs.google.com/document/d/12tZrX2QE6UJuqfV5olonfUuGmHwNiGm4_DLyjrbvG8M/edit?tab=t.0
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'ID' => $this->ID,
            'name' => $this->name,
            'family' => $this->family,
            'mobile' => $this->mobile,
            'type' => $this->type,
            'username' => $this->username,
            'gender' => $this->gender,
            'status' => $this->status,
            'birthDate' => $this->birthDate,
            'nationalCode' => $this->nationalCode,
            'avatarSID' => StorageResource::make($this->avatar),
        ];
    }
}

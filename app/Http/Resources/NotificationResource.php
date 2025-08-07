<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * @link https://docs.google.com/document/d/1B7pNJc5IgAowtApaeejk-OBiEMppGpi9upZRo7lWWCI/edit?tab=t.0
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'ID' => $this->ID,
            'title' => $this->title,
            'content' => $this->content,
            'type' => $this->type,
            'read' => $this->read,
            'created' => $this->created,
        ];
    }


}

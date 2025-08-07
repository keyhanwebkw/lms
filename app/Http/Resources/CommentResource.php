<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Keyhanweb\Subsystem\Http\Resources\StorageResource;

class CommentResource extends JsonResource
{
    /**
     * @link https://docs.google.com/document/d/1V3OzzuOcYHK4ITIf1LueUl406d4cfLbd9PH154LNaRQ/edit?tab=t.0
     *
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $avatar = null;
        if ($manager = $this->authorManager) {
            $author = $manager->name . ' ' . $manager->family;
            // If avatar added for manager in the future, we can add it here like:
            // $avatar = $manager->avatar
        } else {
            $user = $this->authorUser;
            $author = $user->fullname;
            $avatar = $user->storage;
        }

        return [
            'ID' => $this->ID,
            'author' => $author,
            'isAdmin' => (boolean) $this->managerID,
            'content' => $this->content,
            'created' => $this->created,
            'hasReply' => (boolean) $this->hasReply,
            'replies' => CommentResource::collection($this->replies),
            'avatar' => $avatar ? StorageResource::make($avatar) : null,
        ];
    }
}

<?php

namespace App\Http\Resources;

use App\Enums\UserExamStatuses;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserExamResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * @link https://docs.google.com/document/d/10l3_82iOCAzsIhn7R9Ac3w3KFy1ufldn0fp8L_1dboE/edit?tab=t.0
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'ID' => $this->ID,
            'examStatus' => UserExamStatuses::getTranslateFormat($this->examStatus),
            'score' => $this->score,
            'trueAnswers' => $this->trueAnswers,
            'falseAnswers' => $this->falseAnswers,
            'skippedAnswers' => $this->skippedAnswers,
        ];
    }
}

<?php

namespace Database\Factories;

use App\Enums\UserTypes;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Keyhanweb\Subsystem\Enums\UserStatus;

/**
 * @extends Factory<Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $userID = User::query()
            ->where('status', UserStatus::Active)
            ->where('type', UserTypes::Parent)
            ->inRandomOrder()
            ->first()->ID;

        $parent = rand(0, 1) ? Comment::inRandomOrder()->first() : null;
        if ($parent) {
            $parent->hasReply = 1;
            $parent->save();
        }

        return $parent ? [
            'managerID' => 1,
            'content' => $this->faker->paragraph(),
            'parentID' => $parent->ID,
        ] : [
            'userID' => $userID,
            'content' => $this->faker->paragraph(),
        ];
    }
}

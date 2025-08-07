<?php

namespace Database\Factories;

use App\Enums\UserTypes;
use App\Models\User;

/**
 * @extends UserFactory
 */
class UserChildFactory extends UserFactory
{
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'parentID' => User::where('type', UserTypes::Parent->value)->inRandomOrder()->first()?->ID,
            'username' => $this->faker->unique()->userName(),
            'type' => UserTypes::Child->value,
            'birthDate' => $this->faker->dateTimeBetween('-15 years', '-4 years')->getTimestamp(),
        ];
    }
}

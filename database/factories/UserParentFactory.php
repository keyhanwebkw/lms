<?php

namespace Database\Factories;

use App\Models\User;

/**
 * @extends UserFactory
 */
class UserParentFactory extends UserFactory
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
            'family' => $this->faker->lastName(),
            'password' => 1,
            'countryCode' => 98,
            'mobile' => '+98936' . (string)random_int(1000000, 9999999),
            'birthDate' => $this->faker->dateTimeBetween('-40 years', '-20 years')->getTimestamp(),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Keyhanweb\Subsystem\Enums\Gender;
use Keyhanweb\Subsystem\Enums\UserStatus;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $avatarSIDs = [
            'dA000000-0000-0000-0000-00000avatar1',
            'dA000000-0000-0000-0000-00000avatar2',
            'dA000000-0000-0000-0000-00000avatar3',
            'dA000000-0000-0000-0000-00000avatar4',
            'dA000000-0000-0000-0000-00000avatar5',
            'dA000000-0000-0000-0000-00000avatar6',
        ];

        return [
            'name' => $this->faker->firstName(),
            'gender' => $this->faker->randomElement(Gender::values()),
//            'avatarSID' => $this->faker->randomElement($avatarSIDs),
            'nationalCode' => random_int(1234567890, 9876543210),
            'status' => UserStatus::Active->value,
            'registerDate' => time(),
            'lastActivity' => time(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}

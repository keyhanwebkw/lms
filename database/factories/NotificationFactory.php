<?php

namespace Database\Factories;

use App\Enums\NotificationTypes;
use App\Models\Notification;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Notification>
 */
class NotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'content' => $this->faker->paragraph(),
            'type' => $this->faker->randomElement(NotificationTypes::values()),
            'created' => $this->faker->numberBetween(1550000000,1750000000)
        ];
    }
}

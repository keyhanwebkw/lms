<?php

namespace Database\Factories;

use App\Enums\TeacherStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Teacher>
 */
class TeacherFactory extends Factory
{
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
            'family' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'mobile' => '+98936' . (string)random_int(1000000, 9999999),
            'biography' => $this->faker->paragraph(7),
            'avatarSID' => $this->faker->randomElement($avatarSIDs),
            'status' => $this->faker->randomElement(TeacherStatus::values()),
            'birthDate' => $this->faker->dateTimeBetween('-50 years', '-30 years')->getTimestamp(),
            'startEducationDate' => $this->faker->dateTimeBetween('-20 years', '-10 years')->getTimestamp(),
            'startTeachingDate' => $this->faker->dateTimeBetween('-10 years', '-7 years')->getTimestamp(),
            'attendeesCount' => $this->faker->numberBetween(0, 2000),
            'rating' => $this->faker->randomFloat(1,0,10),
            'linkedinProfile' => $this->faker->url(),
            'telegramUsername' => $this->faker->userName(),
            'website' => $this->faker->url(),
        ];
    }
}

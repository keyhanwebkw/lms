<?php

namespace Database\Factories;

use App\Enums\CourseLevels;
use App\Enums\CourseStatuses;
use App\Enums\CourseTypes;
use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(random_int(3, 12), true),
            'description' => $this->faker->paragraph(random_int(10, 15)),
            'duration' => $this->faker->numberBetween(7, 90),
            'type' => $this->faker->randomElement(CourseTypes::cases())->value,
            'price' => $this->faker->numberBetween(200, 2000) . '000',
            'discountAmount' => $this->faker->numberBetween(20, 200) . '000',
            'participants' => $this->faker->numberBetween(10, 100),
            'participantLimitation' => $this->faker->numberBetween(0, 5),
            'status' => $this->faker->randomElement(CourseStatuses::cases())->value,
            'score' => $this->faker->randomDigitNotNull(),
            'teacherID' => $this->faker->randomDigitNotNull(),
            'slug' => $this->faker->slug(5),
            'level' => $this->faker->randomElement(CourseLevels::cases())->value,
            'startDate' => $this->faker->numberBetween(1739000000, 1739900000),
            'endDate' => $this->faker->numberBetween(1740000000, 1790100000),
        ];
    }
}

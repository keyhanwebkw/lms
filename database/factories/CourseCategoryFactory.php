<?php

namespace Database\Factories;

use App\Enums\CourseCategoryStatuses;
use App\Models\CourseCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CourseCategory>
 */
class CourseCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->words(random_int(1, 3), true),
            'description' => $this->faker->paragraph(3),
            'slug' => $this->faker->slug(),
            'photoSID' => 'dA000000-0000-0000-0000-00000avatar1',
            'metaTitle' => $this->faker->words(random_int(1, 3), true),
            'metaDescription' => $this->faker->paragraph(3),
            'metaKeyword' => $this->faker->words(random_int(5, 10), true),
            'status' => $this->faker->randomElement(CourseCategoryStatuses::cases())->value,
        ];
    }
}

<?php

namespace Database\Seeders;

use App\Enums\CommentStatuses;
use App\Models\Comment;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\Teacher;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 10; $i++) {
            $course = Course::factory()->has(
                Comment::factory()->state(['status' => CommentStatuses::APPROVED]
                ),
                'comments'
            )->state([
                'teacherID' => 1,
                'managerID' => 1,

            ])->create();

            $categoryData = CourseCategory::factory()->state([
                'sortOrder' => $i + 1,
            ])->make()->toArray();
            $category = CourseCategory::updateOrCreate($categoryData);
            $course->categories()->attach($category);
        }
    }
}

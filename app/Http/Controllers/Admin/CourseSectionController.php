<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\CourseSectionDatatable;
use App\Http\Requests\Admin\CourseSection\CreateRequest;
use App\Http\Requests\Admin\CourseSection\UpdateRequest;
use App\Models\Course;
use App\Models\CourseSection;
use Keyhanweb\Subsystem\Http\Controllers\Web\Controller;

class CourseSectionController extends Controller
{
    public function list(CourseSectionDatatable $courseSectionDatatable)
    {
        $course = null;
        if ($courseID = request()->courseID) {
            $course = Course::find($courseID);
        }
        $courseFilter = Course::pluck('name', 'ID');

        return $courseSectionDatatable->render(
            'admin.courseSection.list',
            compact('course', 'courseFilter')
        );
    }

    public function create(Course $course)
    {
        return view('admin.courseSection.create', compact('course'));
    }

    public function store(CreateRequest $request, Course $course)
    {
        $data = $request->validated();
        CourseSection::query()
            ->create([
                'courseID' => $course->ID,
                'title' => $data['title'],
                'sortOrder' => $data['sortOrder'],
            ]);
        return back()->with('success', st('Operation done successfully'));
    }

    public function edit(CourseSection $courseSection)
    {
        $course = Course::find($courseSection->courseID);

        return view('admin.courseSection.edit', compact('courseSection', 'course'));
    }

    public function update(UpdateRequest $request, CourseSection $courseSection)
    {
        $data = $request->validated();

        $courseSection->fill($data);
        $courseSection->save();

        return redirect()->route('admin.course.section.list')->with('success', st('Operation done successfully'));
    }
}

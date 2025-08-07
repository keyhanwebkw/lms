<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\CourseDatatable;
use App\Enums\CourseLevels;
use App\Enums\CourseStatuses;
use App\Enums\CourseTypes;
use App\Http\Requests\Admin\Course\CreateRequest;
use App\Http\Requests\Admin\Course\UpdateRequest;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseCategoryPivot;
use App\Models\CourseIntro;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;
use Keyhanweb\Subsystem\Http\Controllers\Web\Controller;
use Keyhanweb\Subsystem\Models\Role;
use Keyhanweb\Subsystem\Models\Storage;
use Keyhanweb\Subsystem\Models\UserRole;

class CourseController extends Controller
{

    /**
     * @param CourseDatatable $courseDatatable
     * @return mixed
     */
    public function list(CourseDatatable $courseDatatable)
    {
        return $courseDatatable->render('admin.course.list');
    }

    /**
     * @return View
     */
    public function create()
    {
        $courseLevels = CourseLevels::valuesTranslate();
        $courseStatuses = CourseStatuses::valuesTranslate();
        $courseTypes = CourseTypes::valuesTranslate();

        $teacherRoleID = Role::query()
            ->where('name', config('subsystem.defaultRoles.teacher.name'))
            ->first()
            ?->ID;
        if (!$teacherRoleID) {
            Artisan::call('db:seed', [
                '--class' => 'Keyhanweb\Subsystem\Database\Seeders\RoleSeeder',
                '--force' => true,
            ]);

            $teacherRoleID = Role::query()
                ->where('name', config('subsystem.defaultRoles.teacher.name'))
                ->first()
                ->ID;
        }

        $teacherIDs = UserRole::query()
            ->where('roleID', $teacherRoleID)
            ->pluck('userID')
            ->toArray();

        $teachers = User::query()
            ->select(
                'ID',
                'name',
                'family'
            )
            ->whereIn('ID', $teacherIDs)
            ->get()
            ->pluck('fullname', 'ID');

        $courseCategories = CourseCategory::query()
            ->select('ID', 'title')
            ->pluck('title', 'ID');

        return view(
            'admin.course.create',
            compact('courseLevels', 'courseStatuses', 'courseTypes', 'teachers', 'courseCategories')
        );
    }

    /**
     * @param CreateRequest $request
     * @return RedirectResponse
     */
    public function store(CreateRequest $request)
    {
        $data = $request->validated();

        $course = Course::create($data + ['managerID' => auth()->id()]);

        foreach (['banner' => 'image', 'introVideo' => 'video'] as $filed => $type) {
            if (!empty($data[$filed])) {
                $storage = Storage::uploadFile(['file' => $data[$filed], 'type' => $type]);
                $SID = $storage->SID;

                $courseIntro = CourseIntro::query()
                    ->create([
                        'courseID' => $course->ID,
                        'type' => $filed,
                        'SID' => $SID,
                    ]);

                $storage->used($courseIntro, true);
            }
        }

        if (!empty($data['courseCategories'])) {
            foreach ($data['courseCategories'] as $courseCategory) {
                CourseCategoryPivot::create([
                    'courseID' => $course->ID,
                    'categoryID' => $courseCategory
                ]);
            }
        }

        return back()->with('success', st('Operation done successfully'));
    }

    /**
     * @param Course $course
     * @return View
     */
    public function edit(Course $course)
    {
        $courseLevels = CourseLevels::valuesTranslate();
        $courseStatuses = CourseStatuses::valuesTranslate();
        $courseTypes = CourseTypes::valuesTranslate();

        $teacherRoleID = Role::query()
            ->where('name', config('subsystem.defaultRoles.teacher.name'))
            ->first()
            ?->ID;
        if (!$teacherRoleID) {
            Artisan::call('db:seed', [
                '--class' => 'Keyhanweb\Subsystem\Database\Seeders\RoleSeeder',
                '--force' => true,
            ]);

            $teacherRoleID = Role::query()
                ->where('name', config('subsystem.defaultRoles.teacher.name'))
                ->first()
                ->ID;
        }

        $teacherIDs = UserRole::query()
            ->where('roleID', $teacherRoleID)
            ->pluck('userID')
            ->toArray();

        $teachers = User::query()
            ->select(
                'ID',
                'name',
                'family'
            )
            ->whereIn('ID', $teacherIDs)
            ->get()
            ->pluck('fullname', 'ID');

        $selectedCategories = CourseCategoryPivot::query()
            ->select('categoryID')
            ->where('courseID', $course->ID)
            ->pluck('categoryID');
        $courseCategories = CourseCategory::query()
            ->select('ID', 'title')
            ->pluck('title', 'ID');

        $courseIntroSIDs = CourseIntro::query()
            ->where('courseID', $course->ID)
            ->pluck('SID', 'type');
        foreach ($courseIntroSIDs as $key => $value) {
            $courseIntroSIDs[$key] = $value;
        }

        return view(
            'admin.course.edit',
            compact(
                'courseLevels',
                'courseStatuses',
                'courseTypes',
                'teachers',
                'courseCategories',
                'course',
                'selectedCategories',
                'courseIntroSIDs',
            )
        );
    }

    public function update(UpdateRequest $request, Course $course)
    {
        $data = $request->validated();

        $course->update($data);

        foreach (['banner' => 'image', 'introVideo' => 'video'] as $filed => $type) {
            if (!empty($data[$filed])) {
                $courseIntro = CourseIntro::query()
                    ->where('courseID', $course->ID)
                    ->where('type', $filed)
                    ->first();
                if (!empty($courseIntro)) {
                    preg_match('/^[^.]*/', $courseIntro->SID, $SID);
                    Storage::deleteBySID($SID[0]);

                    $courseIntro->delete();
                }

                $storage = Storage::uploadFile(['file' => $data[$filed], 'type' => $type]);
                $SID = $storage->SID;

                $newCourseIntro = CourseIntro::query()
                    ->create([
                        'courseID' => $course->ID,
                        'type' => $filed,
                        'SID' => $SID,
                    ]);


                $storage->used($newCourseIntro, true);
            }
        }

        $courseCategories = $course->categories()->allRelatedIds()->toArray();
        if ($courseCategories != $data['courseCategories']) {
            CourseCategoryPivot::query()
                ->where('courseID', $course->ID)
                ->delete();
            $course->categories()->attach($data['courseCategories']);
        }

        return redirect()->route('admin.course.list')->with('success', st('Operation done successfully'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\CourseCategoryDatatable;
use App\Enums\CourseCategoryStatuses;
use App\Http\Requests\Admin\CourseCategory\CreateRequest;
use App\Http\Requests\Admin\CourseCategory\UpdateRequest;
use App\Models\CourseCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Keyhanweb\Subsystem\Http\Controllers\Web\Controller;
use Keyhanweb\Subsystem\Models\Storage;

class CourseCategoryController extends Controller
{

    public function list(CourseCategoryDatatable $courseCategoryDatatable)
    {
        $courseCategoryStatuses = CourseCategoryStatuses::valuesTranslate();
        return $courseCategoryDatatable->render('admin.courseCategory.list', compact(['courseCategoryStatuses']));
    }

    public function create()
    {
        $status = CourseCategoryStatuses::valuesTranslate();
        return view('admin.courseCategory.create', compact('status'));
    }

    public function store(CreateRequest $request)
    {
        $data = $request->validated();
        $isFile = isset($data['photo']);

        if ($isFile) {
            $storage = Storage::uploadFile(['file' => $data['photo'], 'type' => 'image']);
            $data['photoSID'] = $storage->SID;
            unset($data['photo']);
        }

        $courseCategory = CourseCategory::create($data);

        if ($isFile) {
            $storage->used($courseCategory, true);
        }

        return back()->with('success', st('Operation done successfully'));
    }

    /**
     * @param CourseCategory $courseCategory
     * @return View
     */
    public function edit(CourseCategory $courseCategory)
    {
        $status = CourseCategoryStatuses::valuesTranslate();
        $courseCategoryPhotoSID = $courseCategory->photoSID;

        return view('admin.courseCategory.edit', compact('courseCategory', 'status', 'courseCategoryPhotoSID'));
    }

    /**
     * @param UpdateRequest $request
     * @param CourseCategory $courseCategory
     * @return RedirectResponse
     */
    public function update(UpdateRequest $request, CourseCategory $courseCategory)
    {
        $data = $request->validated();
        $isFile = isset($data['photo']);

        if ($isFile) {
            Storage::deleteBySID($courseCategory->photoSID);

            $storage = Storage::uploadFile(['file' => $data['photo'], 'type' => 'image']);
            $data['photoSID'] = $storage->SID . '.' . $storage->extension;
            unset($data['photo']);
        }

        $courseCategory->fill($data);
        $courseCategory->save();

        if ($isFile) {
            $storage->used($courseCategory, true);
        }

        return redirect()->route('admin.courseCategory.list')->with('success', st('Operation done successfully'));
    }
}

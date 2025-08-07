<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\SectionEpisodeDatatable;
use App\Enums\EpisodeStatuses;
use App\Http\Requests\Admin\SectionEpisode\CreateRequest;
use App\Http\Requests\Admin\SectionEpisode\UpdateRequest;
use App\Models\CourseSection;
use App\Models\SectionEpisode;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Keyhanweb\Subsystem\Http\Controllers\Web\Controller;

class SectionEpisodeController extends Controller
{
    public function list(SectionEpisodeDatatable $sectionEpisodeDatatable)
    {
        $courseSection = null;
        if ($courseSectionID = request()->courseSectionID) {
            $courseSection = CourseSection::find($courseSectionID);
        }
        $courseSectionFilter = CourseSection::query()->pluck('title', 'ID');

        return $sectionEpisodeDatatable->render(
            'admin.sectionEpisode.list',
            compact('courseSection', 'courseSectionFilter')
        );
    }

    public function store(CreateRequest $request)
    {
        $data = $request->validated();
        $type = $data['type'];

        match ($type) {
            'content' => $data['episodeContentID'] = 0,
            'assignment' => $data['assignmentID'] = 0,
            'exam' => $data['examID'] = 0,
            default => abort(404),
        };

        unset($data['type']);
        $episode = SectionEpisode::query()
            ->create($data);

        $redirectUrl = match ($type) {
            'assignment' => route('admin.assignment.create', $episode->ID),
            'exam' => route('admin.exam.create', $episode->ID),
            'content' => route('admin.course.section.episode.content.create', $episode->ID),
        };

        return redirect($redirectUrl);
    }

    public function create(CourseSection $courseSection)
    {
        return view('admin.sectionEpisode.create', compact('courseSection'));
    }

    /**
     * @param SectionEpisode $sectionEpisode
     * @return View
     */
    public function edit(SectionEpisode $sectionEpisode)
    {
        $statuses = EpisodeStatuses::valuesTranslate();

        return view('admin.sectionEpisode.edit', compact('sectionEpisode', 'statuses'));
    }

    /**
     * @param UpdateRequest $request
     * @param SectionEpisode $sectionEpisode
     * @return RedirectResponse
     */
    public function update(UpdateRequest $request, SectionEpisode $sectionEpisode)
    {
        $data = $request->validated();

        $sectionEpisode->update([
            'sortOrder' => $data['sortOrder'],
            'status' => $data['status'],
            'isMandatory' => isset($data['isMandatory']),
        ]);

        return redirect()->route(
            'admin.course.section.episode.list',
            ['courseSectionID' => $sectionEpisode->courseSectionID]
        )->with('success', st('Operation done successfully'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\AssignmentDatatable;
use App\Enums\EpisodeStatuses;
use App\Http\Requests\Admin\Assignment\CreateRequest;
use App\Http\Requests\Admin\Assignment\UpdateRequest;
use App\Models\Assignment;
use App\Models\SectionEpisode;
use App\Traits\ValidatesContentFile;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Keyhanweb\Subsystem\Http\Controllers\Web\Controller;
use Keyhanweb\Subsystem\Models\Storage;

class AssignmentController extends Controller
{
    use ValidatesContentFile;

    /**
     * @param AssignmentDatatable $datatable
     * @return mixed
     */
    public function list(AssignmentDatatable $datatable)
    {
        return $datatable->render('admin.assignment.list');
    }

    /**
     * @param CreateRequest $request
     * @param SectionEpisode $sectionEpisode
     * @return RedirectResponse
     */
    public function store(CreateRequest $request, SectionEpisode $sectionEpisode)
    {
        $data = $request->validated();
        $contentExists = isset($data['content']);

        if ($contentExists && !$this->validateContentFile($data['content'], $mime, $error)) {
            return back()->withErrors(['content' => $error]);
        }

        if ($contentExists) {
            $fileType = match (true) {
                str_starts_with($mime, 'image/') => 'image',
                str_starts_with($mime, 'video/') => 'video',
            };

            $storage = Storage::uploadFile(['type' => $fileType, 'file' => $data['content']]);
            $data['contentSID'] = $storage->SID;
        }

        $assignment = Assignment::query()
            ->create([
                'title' => $data['title'],
                'description' => $data['description'],
                'contentSID' => $data['contentSID'] ?? null,
                'deadline' => $data['deadline'],
                'minScoreToPass' => $data['minScoreToPass'],
                'retryCount' => $data['retryCount'],
            ]);

        $sectionEpisode->update([
            'assignmentID' => $assignment->ID,
            'status' => EpisodeStatuses::Published->value,
        ]);

        if ($contentExists) {
            $storage->used($assignment, true);
        }


        return redirect()->route(
            'admin.course.section.episode.list',
            ['courseSectionID' => $sectionEpisode->courseSectionID]
        )
            ->with('success', st('Operation done successfully'));
    }

    /**
     * @param SectionEpisode $sectionEpisode
     * @return View
     */
    public function create(SectionEpisode $sectionEpisode)
    {
        return view('admin.assignment.create', compact('sectionEpisode'));
    }

    public function update(UpdateRequest $request, Assignment $assignment)
    {
        $data = $request->validated();
        $contentExists = isset($data['content']);

        if ($contentExists && !$this->validateContentFile($data['content'], $mime, $error)) {
            return back()->withErrors(['content' => $error]);
        }

        if ($contentExists) {
            Storage::deleteBySID($assignment->contentSID);

            $fileType = match (true) {
                str_starts_with($mime, 'image/') => 'image',
                str_starts_with($mime, 'video/') => 'video',
            };

            $storage = Storage::uploadFile(['type' => $fileType, 'file' => $data['content']]);
            $data['contentSID'] = $storage->SID;
        }

        $assignment->update([
            'title' => $data['title'],
            'description' => $data['description'],
            'contentSID' => $data['contentSID'] ?? $assignment->contentSID,
            'deadline' => $data['deadline'],
            'minScoreToPass' => $data['minScoreToPass'],
            'retryCount' => $data['retryCount'],
        ]);

        if ($contentExists) {
            $storage->used($assignment, true);
        }

        return redirect($data['previousUrl'])->with('success', st('Operation done successfully'));
    }

    public function edit(Assignment $assignment)
    {
        $contentPath = null;
        if ($assignment->contentSID) {
            $storage = Storage::findBySID($assignment->contentSID);
            $contentPath = route('storage.download', ['type' => 'original', 'SID' => $assignment->contentSID]
                ) . '.' . $storage->extension;
        }

        return view('admin.assignment.edit', compact('assignment', 'contentPath'));
    }

    /**
     * @param Assignment $assignment
     * @return RedirectResponse
     */
}

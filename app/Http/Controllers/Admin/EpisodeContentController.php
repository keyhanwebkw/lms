<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\EpisodeContentDatatable;
use App\Enums\EpisodeStatuses;
use App\Http\Requests\Admin\EpisodeContent\CreateRequest;
use App\Http\Requests\Admin\EpisodeContent\UpdateRequest;
use App\Models\EpisodeContent;
use App\Models\SectionEpisode;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Keyhanweb\Subsystem\Http\Controllers\Web\Controller;
use Keyhanweb\Subsystem\Models\Storage;

class EpisodeContentController extends Controller
{
    /**
     * @param EpisodeContentDatatable $datatable
     * @return mixed
     */
    public function list(EpisodeContentDatatable $datatable)
    {
        return $datatable->render('admin.sectionEpisode.content.list');
    }

    /**
     * @param CreateRequest $request
     * @param SectionEpisode $sectionEpisode
     * @return RedirectResponse
     */
    public function store(CreateRequest $request, SectionEpisode $sectionEpisode)
    {
        $data = $request->validated();

        $episodeContent = EpisodeContent::query()
            ->create([
                'title' => $data['title'],
                'duration' => $data['duration'],
                'description' => $data['description'],
                'contentSID' => $data['contentSID'],
            ]);

        $sectionEpisode->update([
            'episodeContentID' => $episodeContent->ID,
            'status' => EpisodeStatuses::Published->value,
        ]);


        $storage = Storage::findBySID($data['contentSID']);
        $storage->isUsed = 1;
        $storage->isPublic = 0;
        $storage->save();

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
        return view('admin.sectionEpisode.content.create', compact('sectionEpisode'));
    }

    /**
     * @param UpdateRequest $request
     * @param SectionEpisode $sectionEpisode
     * @return RedirectResponse
     */
    public function update(UpdateRequest $request, EpisodeContent $episodeContent)
    {
        $data = $request->validated();

        $episodeContent->update([
            'title' => $data['title'],
            'duration' => $data['duration'],
            'description' => $data['description'],
            'contentSID' => $data['contentSID'],
        ]);

        $storage = Storage::findBySID($data['contentSID']);
        $storage->isUsed = 1;
        $storage->isPublic = 0;
        $storage->save();

        return redirect($data['returnUrl'])->with('success', st('Operation done successfully'));
    }

    /**
     * @param EpisodeContent $episodeContent
     * @return View
     */
    public function edit(EpisodeContent $episodeContent)
    {
        $storage = Storage::findBySID($episodeContent->contentSID);
        $contentUrl = Storage::getStorageUrl($storage);

        return view('admin.sectionEpisode.content.edit', compact('episodeContent', 'contentUrl'));
    }

}

<?php

namespace App\Http\Controllers\Admin\ChildGame;

use App\DataTables\ChildGame\SeasonEpisodeDatatable;
use App\Http\Requests\Admin\ChildGame\SeasonEpisode\CreateRequest;
use App\Http\Requests\Admin\ChildGame\SeasonEpisode\UpdateRequest;
use App\Models\Movie;
use App\Models\MovieSeason;
use App\Models\SeasonEpisode;
use Keyhanweb\Subsystem\Http\Controllers\Web\Controller;
use Keyhanweb\Subsystem\Models\Storage;

class SeasonEpisodeController extends Controller
{
    public function store(CreateRequest $request)
    {
        $data = $request->validated();

        $relatedTo = match (true) {
            isset($data['seasonID']) => MovieSeason::findOrFail(request('seasonID')),
            isset($data['movieID']) => Movie::findOrFail(request('movieID')),
            default => null
        };

        if (!$relatedTo) {
            return back()->withErrors('Required parameter is missing');
        }

        SeasonEpisode::create([
            'title' => $data['title'] ?? null,
            'seasonID' => $data['seasonID'] ?? null,
            'movieID' => $data['movieID'] ?? null,
            'videoSID' => $data['videoSID'] ?? null,
            'videoUrl' => $data['videoUrl'] ?? null,
            'sortOrder' => $data['sortOrder'] ?? null,
        ]);

        if (isset($data['videoSID'])) {
            $storage = Storage::findBySID($data['videoSID']);

            $storage->isUsed = 1;
            $storage->save();
        }

        return redirect($data['returnUrl'])->with('success', st('Operation done successfully'));
    }

    public function create()
    {
        $movieSeason = null;
        $movie = null;

        if (request('movieSeasonID')) {
            $movieSeason = MovieSeason::findOrFail(request('movieSeasonID'));
        } elseif (request('movieID')) {
            $movie = Movie::findOrFail(request('movieID'));
        } else {
            return back()->withErrors('Required parameter is missing');
        }

        return view('admin.childGame.seasonEpisode.create', compact('movieSeason', 'movie'));
    }

    public function list(SeasonEpisodeDatatable $dataTable)
    {
        $isAllowedToCreateFilm = false;

        if (request('movieID')) {
            $isSeasonEpisodeExists = SeasonEpisode::query()
                ->where('movieID', request('movieID'))
                ->exists();

            $isAllowedToCreateFilm = !$isSeasonEpisodeExists;
        }

        return $dataTable->render('admin.childGame.seasonEpisode.list', compact('isAllowedToCreateFilm'));
    }

    public function edit(SeasonEpisode $seasonEpisode)
    {
        $videoUrl = null;
        $movieSeason = null;
        $movie = null;

        match (true) {
            isset($seasonEpisode->seasonID) => $movieSeason = $seasonEpisode,
            isset($seasonEpisode->movieID) => $movie = $seasonEpisode,
        };

        if ($seasonEpisode->videoSID) {
            $storage = Storage::findBySID($seasonEpisode->videoSID);
            if ($storage) {
                $videoUrl = Storage::getStorageUrl($storage);
            }
        }

        return view('admin.childGame.seasonEpisode.edit', compact('movieSeason', 'movie', 'videoUrl'));
    }

    public function update(UpdateRequest $request, SeasonEpisode $seasonEpisode)
    {
        $data = $request->validated();

        $seasonEpisode->update([
            'title' => $data['title'] ?? null,
            'videoSID' => $data['videoSID'] ?? null,
            'videoUrl' => $data['videoUrl'] ?? null,
            'sortOrder' => $data['sortOrder'] ?? null,
        ]);

        return redirect($data['returnUrl'])->with('success', st('Operation done successfully'));
    }

    public function delete(SeasonEpisode $seasonEpisode)
    {
        if (isset($seasonEpisode->videoSID)) {
            Storage::deleteBySid($seasonEpisode->videoSID);
        }

        $seasonEpisode->markAsDeleted();

        $parameter = match (true) {
            isset($seasonEpisode->movieID) => ['key' => 'movieID', 'value' => $seasonEpisode->movieID],
            isset($seasonEpisode->seasonID) => ['key' => 'movieSeasonID', 'value' => $seasonEpisode->seasonID],
        };

        return redirect()->route('admin.cg.seasonEpisode.list', [$parameter['key'] => $parameter['value']])->with(
            'success',
            st('Operation done successfully')
        );
    }
}

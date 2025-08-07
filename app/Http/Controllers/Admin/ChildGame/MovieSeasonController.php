<?php

namespace App\Http\Controllers\Admin\ChildGame;

use App\DataTables\ChildGame\MovieSeasonDatatable;
use App\Http\Requests\Admin\ChildGame\MovieSeason\CreateRequest;
use App\Http\Requests\Admin\ChildGame\MovieSeason\UpdateRequest;
use App\Models\Movie;
use App\Models\MovieSeason;
use Keyhanweb\Subsystem\Http\Controllers\Web\Controller;

class MovieSeasonController extends Controller
{
    public function store(CreateRequest $request, Movie $movie)
    {
        $data = $request->validated();
        $data['movieID'] = $movie->ID;

        MovieSeason::create($data);

        return back()->with('success', st('Operation done successfully'));
    }

    public function create(Movie $movie)
    {
        return view('admin.childGame.movieSeason.create', compact('movie'));
    }

    public function list(MovieSeasonDataTable $dataTable)
    {
        return $dataTable->render('admin.childGame.movieSeason.list');
    }

    public function edit(MovieSeason $movieSeason)
    {
        return view('admin.childGame.movieSeason.edit', compact('movieSeason'));
    }

    public function update(UpdateRequest $request, MovieSeason $movieSeason)
    {
        $data = $request->validated();

        $movieSeason->update($data);

        return redirect()->route('admin.cg.movieSeason.list', ['movieID' => $movieSeason->movieID])->with('success', st('Operation done successfully'));
    }

    public function archive(MovieSeason $movieSeason)
    {
        $movieSeason->archive();

        return back()->with('success', st('Operation done successfully'));
    }

    public function unarchive(MovieSeason $movieSeason)
    {
        $movieSeason->unarchive();

        return back()->with('success', st('Operation done successfully'));
    }
}

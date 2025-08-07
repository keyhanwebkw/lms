<?php

namespace App\Http\Controllers\Admin\ChildGame;

use App\DataTables\ChildGame\MovieDatatable;
use App\Enums\MovieTypes;
use App\Http\Requests\Admin\ChildGame\Movie\CreateRequest;
use App\Http\Requests\Admin\ChildGame\Movie\UpdateRequest;
use App\Models\Movie;
use App\Models\MovieCategory;
use App\Models\MovieCategoryPivot;
use Keyhanweb\Subsystem\Http\Controllers\Web\Controller;
use Keyhanweb\Subsystem\Models\Storage;

class MovieController extends Controller
{
    public function list(MovieDatatable $dataTable)
    {
        $types = MovieTypes::valuesTranslate();

        return $dataTable->render('admin.childGame.movie.list', compact('types'));
    }

    public function store(CreateRequest $request)
    {
        $data = $request->validated();

        $storage = Storage::uploadFile(['file' => $data['poster'], 'type' => 'image']);
        $data['posterSID'] = $storage->SID;

        unset($data['poster']);

        $movie = Movie::create($data);

        $storage->used($movie, true);

        $insertQueryData = [];
        foreach ($data['movieCategories'] as $movieCategories) {
            $insertQueryData[] = [
                'movieID' => $movie->ID,
                'categoryID' => $movieCategories
            ];
        }

        MovieCategoryPivot::insert($insertQueryData);

        return back()->with('success', st('Operation done successfully'));
    }

    public function create()
    {
        $types = MovieTypes::valuesTranslate();

        $categories = MovieCategory::query()
            ->orderBy('sortOrder')
            ->pluck('title', 'ID');

        return view('admin.childGame.movie.create', compact('types', 'categories'));
    }

    public function edit(Movie $movie)
    {
        $types = MovieTypes::valuesTranslate();

        $selectedCategories = MovieCategoryPivot::query()
            ->where('movieID', $movie->ID)
            ->pluck('categoryID');

        $categories = MovieCategory::query()
            ->orderBy('sortOrder')
            ->pluck('title', 'ID');

        $storage = Storage::findBySID($movie->posterSID);
        $posterUrl = Storage::getStorageUrl($storage);

        return view(
            'admin.childGame.movie.edit',
            compact('movie', 'posterUrl', 'types', 'categories', 'selectedCategories')
        );
    }

    public function update(UpdateRequest $request, Movie $movie)
    {
        $data = $request->validated();

        if (isset($data['poster'])) {
            Storage::deleteBySID($movie->posterSID);

            $storage = Storage::uploadFile(['file' => $data['poster'], 'type' => 'image']);
            $data['posterSID'] = $storage->SID;

            unset($data['poster']);
        }

        $movie->update([
            'name' => $data['name'],
            'slug' => $data['slug'],
            'posterSID' => $data['posterSID'] ?? $movie->posterSID,
            'description' => $data['description'],
            'type' => $data['type'],
        ]);

        if (isset($data['posterSID'])) {
            $storage->used($movie, true);
        }

        $currentCategories = MovieCategoryPivot::query()
            ->where('movieID', $movie->ID)
            ->pluck('categoryID')
            ->toArray();

        $currentCategories = json_encode(array_map('strval', $currentCategories));
        $newCategories = json_encode($data['movieCategories']);


        if ($currentCategories !== $newCategories) {
            $insertQueryData = [];

            MovieCategoryPivot::query()
                ->where('movieID', $movie->ID)
                ->delete();

            foreach ($data['movieCategories'] as $category) {
                $insertQueryData[] = [
                    'movieID' => $movie->ID,
                    'categoryID' => $category,
                ];
            }

            MovieCategoryPivot::insert($insertQueryData);
        }


        return redirect()->route('admin.cg.movie.list')->with('success', st('Operation done successfully'));
    }

    public function archive(Movie $movie)
    {
        $movie->archive();

        return back()->with('success', st('Operation done successfully'));
    }

    public function unarchive(Movie $movie)
    {
        $movie->unarchive();

        return back()->with('success', st('Operation done successfully'));
    }

}

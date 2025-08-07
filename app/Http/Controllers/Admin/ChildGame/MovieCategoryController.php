<?php

namespace App\Http\Controllers\Admin\ChildGame;

use App\DataTables\ChildGame\MovieCategoryDatatable;
use App\Http\Requests\Admin\ChildGame\MovieCategory\CreateRequest;
use App\Http\Requests\Admin\ChildGame\MovieCategory\UpdateRequest;
use App\Models\MovieCategory;
use Keyhanweb\Subsystem\Http\Controllers\Web\Controller;
use Keyhanweb\Subsystem\Models\Storage;

class MovieCategoryController extends Controller
{
    public function list(MovieCategoryDataTable $dataTable)
    {
        return $dataTable->render('admin.childGame.movieCategory.list');
    }

    public function store(CreateRequest $request)
    {
        $data = $request->validated();

        $storage = Storage::uploadFile(['file' => $data['photo'], 'type' => 'image']);
        $data['photoSID'] = $storage->SID;

        unset($data['photo']);

        $movieCategory = MovieCategory::create($data);

        $storage->used($movieCategory, true);

        return back()->with('success', st('Operation done successfully'));
    }

    public function create()
    {
        return view('admin.childGame.movieCategory.create');
    }

    public function edit(MovieCategory $movieCategory)
    {
        $storage = Storage::findBySID($movieCategory->photoSID);
        $photoUrl = Storage::getStorageUrl($storage);

        return view('admin.childGame.movieCategory.edit', compact('movieCategory', 'photoUrl'));
    }

    public function update(UpdateRequest $request, MovieCategory $movieCategory)
    {
        $data = $request->validated();

        if (isset($data['photo'])) {
            Storage::deleteBySID($movieCategory->photoSID);

            $storage = Storage::uploadFile(['file' => $data['photo'], 'type' => 'image']);
            $data['photoSID'] = $storage->SID;

            unset($data['photo']);
        }

        $movieCategory->update([
            'title' => $data['title'],
            'slug' => $data['slug'],
            'photoSID' => $data['photoSID'] ?? $movieCategory->photoSID,
            'description' => $data['description'],
            'sortOrder' => $data['sortOrder'],
        ]);

        if (isset($data['photoSID'])) {
            $storage->used($movieCategory, true);
        }

        return redirect()->route('admin.cg.movieCategory.list')->with('success', st('Operation done successfully'));
    }

    public function archive(MovieCategory $movieCategory)
    {
        $movieCategory->archive();

        return back()->with('success', st('Operation done successfully'));
    }

    public function unarchive(MovieCategory $movieCategory)
    {
        $movieCategory->unarchive();

        return back()->with('success', st('Operation done successfully'));
    }
}

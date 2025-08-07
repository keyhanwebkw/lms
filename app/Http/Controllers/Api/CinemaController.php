<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\Cinema\IndexRequest;
use App\Http\Resources\ChildGame\MovieCategoryResource;
use App\Models\MovieCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Keyhanweb\Subsystem\Http\Controllers\Api\Controller;

class CinemaController extends Controller
{
    // ----------------- Errors -----------------
    private int $CATEGORY_NOT_FOUND = 1;

    // ----------------- Logic -----------------

    /**
     * @param IndexRequest $request
     * @return JsonResponse
     * @link https://docs.google.com/document/d/11I5FuOC34Q7JGOZX5Oj5Ug8RB3KEaRGMQ7WWqdu1lcc/edit?tab=t.0
     */
    public function index(IndexRequest $request)
    {
        $data = $request->validated();
        $isListRequest = isset($data['categorySlug']);

        $cacheKey = MovieCategory::keyCache('list' . ($isListRequest ? ('_' . $data['categorySlug']) : ''));
        $movieCategories = Cache::tags(MovieCategory::cacheTag())->remember(
            $cacheKey,
            now()->addHours(3),
            function () use ($isListRequest, $data) {
                return MovieCategory::query()
                    ->with([
                        'movies' => function ($query) use ($isListRequest, $data) {
                            return $query
                                ->when(!$isListRequest, function ($query) {
                                    return $query->limit(10);
                                })
                                ->when($isListRequest, function ($query) use ($data) {
                                    return $query->pageLimit($data['page'] ?? null, $data['itemsPerPage'] ?? 10);
                                });
                        },
                        'movies.seasons' => function ($query) {
                            return $query->orderBy('sortOrder');
                        },
                        'movies.seasons.episodes' => function ($query) {
                            return $query->orderBy('sortOrder');
                        },
                        'movies.content',
                        // Storage relations
                        'storage', // MovieCategory photo
                        'movies.content.storage', // FeatureFilm video
                        'movies.seasons.episodes.storage', // Episodes video
                    ])
                    ->orderBy('sortOrder')
                    ->when($isListRequest, function ($query) use ($data) {
                        return $query
                            ->where('slug', $data['categorySlug'])
                            ->get();
                    })
                    ->when(!$isListRequest, function ($query) use ($data) {
                        return $query->pageLimit($data['page'] ?? null, $data['itemsPerPage'] ?? 10);
                    });
            }
        );

        if ($isListRequest && $movieCategories->isEmpty()) {
            return $this->error($this->CATEGORY_NOT_FOUND, st('The selected category is invalid'));
        }

        return $this->success([
            'categories' => MovieCategoryResource::collection($movieCategories),
        ]);
    }
}

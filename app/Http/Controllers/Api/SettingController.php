<?php

namespace App\Http\Controllers\Api;

use App\Enums\CourseIntroTypes;
use App\Enums\UserTypes;
use App\Http\Resources\CourseSummaryResource;
use App\Models\Course;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Keyhanweb\Subsystem\Enums\UserStatus;
use Keyhanweb\Subsystem\Http\Controllers\Api\Controller;
use Keyhanweb\Subsystem\Http\Resources\ArticleSummaryResource;
use Keyhanweb\Subsystem\Http\Resources\StorageResource;
use Keyhanweb\Subsystem\Models\Article;
use Keyhanweb\Subsystem\Models\Storage;

class SettingController extends Controller
{
    /**
     * @return JsonResponse
     * @link https://docs.google.com/document/d/1vGwom5jsOIp2599dBozmsfiGoikK5g7jniMBkEyofD8/edit?tab=t.0
     */
    public function indexPage()
    {
        $now = time();

        // User balance, score and last logged child
        $user = Auth::user();
        $lastLoggedChild = User::query()
            ->select(
                'ID',
                'name',
            )
            ->where('type', UserTypes::Child->value)
            ->where('status', UserStatus::Active->value)
            ->where('parentID', $user->ID)
            ->orderBy('lastActivity')
            ->first();

        // Settings
        $cacheKey = Setting::cacheKey('indexPage');
        $settings = Cache::tags(Setting::cacheTag())->remember($cacheKey, now()->addHours(6), function () {
            return Setting::query()
                ->where('relatedTo', 'indexPage')
                ->get();
        });

        // Quick access buttons
        $homeQuickAccesses = $settings->where('key', 'homeQuickAccesses')->first();
        $homeQuickAccesses = array_filter($homeQuickAccesses->value);
        // Icon's storage
        foreach ($homeQuickAccesses as $index => $item) {
            $storage = Storage::findBySID($item['iconSID']);
            $storageResource = StorageResource::make($storage);
            $homeQuickAccesses[$index]['icon'] = $storageResource;
            unset($homeQuickAccesses[$index]['iconSID']);
        }

        // Banners
        $homeBanners = $settings->where('key', 'homeBanners')->first();
        $homeBanners = array_filter($homeBanners->value);
        // Banners's storage
        foreach ($homeBanners as $index => $item) {
            if ($item['startDisplayDate'] < $now && $item['endDisplayDate'] > $now) {
                $storage = Storage::findBySID($item['bannerSID']);
                $storageResource = StorageResource::make($storage);
                $homeBanners[$index]['banner'] = $storageResource;
                unset($homeBanners[$index]['bannerSID']);
                unset($homeBanners[$index]['startDisplayDate']);
                unset($homeBanners[$index]['endDisplayDate']);
            } else {
                unset($homeBanners[$index]);
            }
        }

        // Latest Articles
        $homeLatestArticles = $settings->where('key', 'homeLatestArticles')->first();
        $homeLatestArticlesIDs = array_unique(array_filter($homeLatestArticles->value));
        $cacheKey = Article::keyCache('_IDs_' . implode('_', $homeLatestArticlesIDs));
        $articles = Cache::tags(Article::cacheTag())->remember(
            $cacheKey,
            now()->addHours(6),
            function () use ($homeLatestArticlesIDs) {
                return Article::query()
                    ->select([
                        'ID',
                        'title',
                        'slug',
                        'readingTime',
                        'posterSID',
                        'created'
                    ])
                    ->whereIn('ID', $homeLatestArticlesIDs)
                    ->with('storage', 'categories:ID,title,slug')
                    ->get();
            }
        );

        // Latest Courses
        $homeLatestCourses = $settings->where('key', 'homeLatestCourses')->first();
        $homeLatestCoursesIDs = array_unique(array_filter($homeLatestCourses->value));

        $cacheKey = Course::keyCache('_IDs_' . implode('_', $homeLatestCoursesIDs));
        // Same as course list API
        $courses = Cache::tags(Course::cacheTag())->remember(
            $cacheKey,
            now()->addHours(6),
            function () use ($homeLatestCoursesIDs, $now) {
                return Course::query()
                    ->select(
                        'ID',
                        'name',
                        'description',
                        'type',
                        'duration',
                        'price',
                        'discountAmount',
                        'status',
                        'score',
                        'teacherID',
                        'slug',
                        'startDate',
                        'endDate'
                    )
                    ->whereIn('ID', $homeLatestCoursesIDs)
                    ->with([
                        'teacher' => function ($query) {
                            $query->select('ID', 'name', 'family', 'avatarSID')->with('storage');
                        },
                        'courseIntro' => function ($query) {
                            $query->where('type', CourseIntroTypes::Banner->value)->with('storage');
                        },
                        'categories'
                    ])
                    ->where('startDate', '<', $now)
                    ->get();
            }
        );

        return $this->success([
            'balance' => $user->balance ?? 0,
            'score' => $user->score ?? 0,
            'childInfo' => $lastLoggedChild,
            'quickAccesses' => $homeQuickAccesses,
            'banners' => $homeBanners,
            'latestArticles' => ArticleSummaryResource::collection($articles),
            'latestCourses' => CourseSummaryResource::collection($courses),
        ]);
    }
}

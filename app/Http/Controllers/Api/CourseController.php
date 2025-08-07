<?php

namespace App\Http\Controllers\Api;

use App\Enums\CourseIntroTypes;
use App\Enums\EpisodeStatuses;
use App\Http\Requests\Api\Course\ContentRequest;
use App\Http\Requests\Api\Course\EpisodeGetRequest;
use App\Http\Requests\Api\Course\GetRequest;
use App\Http\Requests\Api\Course\JoinFreeRequest;
use App\Http\Requests\Api\Course\ListRequest;
use App\Http\Requests\Api\Course\PurchasedListRequest;
use App\Http\Requests\Api\Course\RelatedRequest;
use App\Http\Resources\AssignmentEpisodeResource;
use App\Http\Resources\ContentEpisodeResource;
use App\Http\Resources\CourseCategoryResource;
use App\Http\Resources\CourseResource;
use App\Http\Resources\CourseSummaryResource;
use App\Http\Resources\EpisodeContentResource;
use App\Http\Resources\ExamEpisodeResource;
use App\Http\Resources\PurchasedCourseResource;
use App\Http\Resources\RelatedCourseResource;
use App\Models\Assignment;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseCategoryPivot;
use App\Models\CourseSection;
use App\Models\EpisodeContent;
use App\Models\Exam;
use App\Models\Notification;
use App\Models\SectionEpisode;
use App\Models\UserCourse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class CourseController extends ApiController
{
    /**
     * @link https://docs.google.com/document/d/1H4y46c4xWpSMOqIb14nWwthGD6wuVZoPwfeC5Cs6VWA/edit?usp=sharing
     * @return JsonResponse
     */
    public function categoryList()
    {
        $courseCategory = Cache::tags(CourseCategory::cacheTag())->remember(
            CourseCategory::keyCache(),
            now()->addMinutes(60),
            function () {
                return CourseCategory::query()
                    ->with('storage')
                    ->orderBy('sortOrder')
                    ->get();
            }
        );

        return $this->success([
            'categories' => CourseCategoryResource::collection($courseCategory),
        ]);
    }

    /**
     * @param GetRequest $request
     * @return JsonResponse
     * @link
     */
    public function get(GetRequest $request)
    {
        $data = $request->validated();
        $slug = $data['slug'];

        $cacheKey = Course::keyCache('_slug_' . $slug);
        $course = Cache::tags(Course::cacheTag())->remember($cacheKey, now()->addMinutes(5), function () use ($slug) {
            $now = time();
            return Course::query()
                ->with([
                    'teacher' => function ($query) {
                        $query->select('ID', 'name', 'family', 'avatarSID')->with('storage');
                    },
                    'courseSection' => function ($query) {
                        $query->select('ID', 'courseID', 'title')->orderBy('sortOrder', 'asc');
                    },
                    'courseIntro' => function ($query) {
                        $query->where('type', CourseIntroTypes::IntroVideo->value)->with('storage');
                    },
                    'categories'
                ])
                ->where('slug', $slug)
                ->where('startDate', '<', $now)
                ->first();
        });

        if (!$course) {
            return $this->error(1, st('record not found'));
        }

        $userID = Auth::id();
        $hasBought = UserCourse::query()
            ->where('userID', $userID)
            ->where('courseID', $course->ID)
            ->exists();

        return $this->success([
            'course' => CourseResource::make($course),
            'hasBought' => $hasBought,
        ]);
    }

    /**
     * @link https://docs.google.com/document/d/1xJaxRxiokSciMN8f0R7ywzE7-XOST_F7erKUsTAhvAY/edit?usp=sharing
     * @param ListRequest $request
     * @return JsonResponse
     */
    public function list(ListRequest $request)
    {
        $data = $request->validated();

        $courseIDs = [];
        if (!empty($data['categorySlug']) || !empty($data['categoryID'])) {
            $cacheKey = CourseCategory::keyCache('_' . ($data['categorySlug'] ?? $data['categoryID']));
            $courseCategory = CourseCategory::query()
                ->when(!empty($data['categorySlug']), function ($query) use ($data) {
                    return $query->where('slug', $data['categorySlug']);
                })
                ->when(!empty($data['categoryID']), function ($query) use ($data) {
                    return $query->where('ID', $data['categoryID']);
                })
                ->first();
            if (empty($courseCategory)) {
                return $this->error(422, st('The selected category is invalid'));
            }

            $courseIDs = Cache::tags(CourseCategory::cacheTag())->remember(
                $cacheKey,
                now()->addMinutes(10),
                function () use ($courseCategory) {
                    return CourseCategoryPivot::query()
                        ->where('categoryID', $courseCategory->ID)
                        ->pluck('courseID');
                }
            );
        }

        $cacheKey = Course::keyCache('_list_' . md5(json_encode(array_filter($data))));
        $courses = Cache::tags(Course::cacheTag())->remember(
            $cacheKey,
            now()->addMinutes(5),
            function () use ($courseIDs, $data) {
                $now = time();
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
                    ->when(!empty($courseIDs), function ($query) use ($courseIDs) {
                        $query->whereIn('ID', $courseIDs);
                    })
                    ->when(!empty($data['slug']), function ($query) use ($data) {
                        $query->where('slug', $data['slug']);
                    })
                    ->when(!empty($data['name']), function ($query) use ($data) {
                        $query->where('name', 'like', '%' . $data['name'] . '%');
                    })
                    ->when(!empty($data['sort']), function ($query) use ($data) {
                        foreach ($data['sort'] as $field => $direction) {
                            $query->orderBy($field, $direction);
                        }
                    })
                    ->pageLimit($data['page'] ?? null, $data['itemsPerPage'] ?? null);
            }
        );

        return $this->success([
            'courses' => CourseSummaryResource::collection($courses),
            'totalRecords' => $courses->totalRecords,
            'hasNextPage' => $courses->hasNextPage,
        ]);
    }

    /**
     * @link https://docs.google.com/document/d/1tyROPjQr42fTF1TALBqZ0adtrKNnQ-2oLrv7lVx9ATo/edit?tab=t.0
     * @param EpisodeGetRequest $request
     * @return JsonResponse
     */
    public function episodeGet(EpisodeGetRequest $request)
    {
        $data = $request->validated();
        $response = [];

        $cacheKey = SectionEpisode::keyCache('_list_' . $data['courseSectionID']);
        $episodes = Cache::tags(SectionEpisode::cacheTag())->remember(
            $cacheKey,
            now()->addMinutes(15),
            function () use ($data) {
                return SectionEpisode::query()
                    ->where('courseSectionID', $data['courseSectionID'])
                    ->where('status', EpisodeStatuses::Published->value)
                    ->orderBy('sortOrder')
                    ->get();
            }
        );

        $cacheKey = CourseSection::keyCache('_ID_' . $data['courseSectionID']);
        $courseSection = Cache::tags(CourseSection::cacheTag())->remember(
            $cacheKey,
            now()->addMinutes(15),
            function () use ($data) {
                return CourseSection::query()
                    ->where('ID', $data['courseSectionID'])
                    ->first();
            }
        );

        $hasBought = false;
        if ($courseSection) {
            $userID = Auth::id();
            $hasBought = UserCourse::query()
                ->where('userID', $userID)
                ->where('courseID', $courseSection->courseID)
                ->exists();
        }

        if ($episodes->isNotEmpty()) {
            $episodes->each(function ($episode) use (&$response, $hasBought, $data) {
                $response[] = match (true) {
                    isset($episode->episodeContentID) => $this->episodeContentWrapper(
                        $episode->episodeContentID,
                        $hasBought,
                        $data['courseSectionID'],
                        $episode->isMandatory,
                    ),
                    isset($episode->assignmentID) => $this->assignmentWrapper(
                        $episode->assignmentID,
                        $hasBought,
                        $data['courseSectionID'],
                        $episode->isMandatory,
                    ),
                    isset($episode->examID) => $this->examWrapper(
                        $episode->examID,
                        $hasBought,
                        $data['courseSectionID'],
                        $episode->isMandatory,
                    ),
                };
            });
        }

        return $this->success([
            'episodes' => $response,
        ]);
    }

    private function episodeContentWrapper($episodeContentID, $hasBought, $courseSectionID, $isMandatory): ContentEpisodeResource
    {
        $episodeContent = $hasBought
            // For who has bought the course, we won't cache to be sure for data validity
            ? EpisodeContent::findOrFail($episodeContentID)
            // But for normal users, we will cache it to prevent additional queries
            : Cache::tags(EpisodeContent::cacheTag())->remember(
                EpisodeContent::keyCache('_' . $episodeContentID),
                now()->addMinutes(15),
                function () use ($episodeContentID) {
                    return EpisodeContent::findOrFail($episodeContentID);
                }
            );

        // TODO: We should check that user has done this step and its permission at here
        $boolean = (bool)rand(0, 1);
        $permission = $boolean;
        $isDone = false;

        // Logic
        $permission = $hasBought && $permission;

        $episodeContent->permission = $permission;
        $episodeContent->isDone = $isDone;
        $episodeContent->courseSectionID = $courseSectionID;

        return ContentEpisodeResource::make($episodeContent);
    }

    private function assignmentWrapper($assignmentID, $hasBought, $courseSectionID, $isMandatory): AssignmentEpisodeResource
    {
        $assignment = $hasBought
            // For who has bought the course, we won't cache to be sure for data validity
            ? Assignment::findOrFail($assignmentID)
            // But for normal users, we will cache it to prevent additional queries
            : Cache::tags(Assignment::cacheTag())->remember(
                Assignment::keyCache('_' . $assignmentID),
                now()->addMinutes(15),
                function () use ($assignmentID) {
                    return Assignment::findOrFail($assignmentID);
                }
            );

        // TODO: We should check that user has done this step and its permission at here
        $boolean = (bool)rand(0, 1);
        $permission = $boolean;
        $isDone = false;

        // Logic
        $permission = $hasBought && $permission;

        $assignment->permission = $permission;
        $assignment->isDone = $isDone;
        $assignment->courseSectionID = $courseSectionID;

        return AssignmentEpisodeResource::make($assignment);
    }

    private function examWrapper($examID, $hasBought, $courseSectionID, $isMandatory): ExamEpisodeResource
    {
        $exam = $hasBought
            // For who has bought the course, we won't cache to be sure for data validity
            ? Exam::query()
                ->where('ID', $examID)
                ->withCount('questions as questionCount')
                ->first()
            // But for normal users, we will cache it to prevent additional queries
            : Cache::tags(Exam::cacheTag())->remember(
                Exam::keyCache('_' . $examID),
                now()->addMinutes(15),
                function () use ($examID) {
                    return Exam::query()
                        ->where('ID', $examID)
                        ->withCount('questions as questionCount')
                        ->first();
                }
            );

        // TODO: We should check that user has done this step and its permission at here
        $boolean = (bool)rand(0, 1);
        $permission = $boolean;
        $isDone = false;

        // Logic
        $permission = $hasBought && $permission;

        $exam->permission = $permission;
        $exam->isDone = $isDone;
        $exam->courseSectionID = $courseSectionID;

        return ExamEpisodeResource::make($exam);
    }

    /**
     * @param RelatedRequest $request
     * @return JsonResponse
     * @link https://docs.google.com/document/d/1VaYoOkDeYxoTEPdfhf5XVUez2QTlgNieF3vSJzyCjZ4/edit?tab=t.0
     */
    public function related(RelatedRequest $request)
    {
        $data = $request->validated();
        $slug = $data['slug'];
        $now = time();


        $cacheKey = Course::keyCache('_slug_' . $slug);
        $course = Cache::tags(Course::cacheTag())->remember(
            $cacheKey,
            now()->addMinutes(30),
            function () use ($slug, $now) {
                return Course::query()
                    // Cache keys are same so queries should be same too
                    ->with([
                        'teacher' => function ($query) {
                            $query->select('ID', 'name', 'family', 'avatarSID')->with('storage');
                        },
                        'courseSection' => function ($query) {
                            $query->select('ID', 'courseID', 'title')->orderBy('sortOrder', 'asc');
                        },
                        'courseIntro' => function ($query) {
                            $query->where('type', CourseIntroTypes::IntroVideo->value)->with('storage');
                        },
                        'categories'
                    ])
                    ->where('slug', $slug)
                    ->where('startDate', '<', $now)
                    ->first();
            }
        );

        if (!$course) {
            return $this->error(1, st('record not found'));
        }

        $categoriesID = $course->categories
            ->pluck('ID')
            ->toArray();

        $courseIDs = CourseCategoryPivot::query()
            ->whereIn('categoryID', $categoriesID)
            ->where('courseID', '!=', $course->ID)
            ->orderByDesc('ID')
            ->pageLimit($data['page'] ?? null, $data['itemsPerPage'] ?? 5);

        $hasNextPage = $courseIDs->hasNextPage;
        $courseIDs = $courseIDs->pluck('courseID')
            ->toArray();

        $cacheKey = Course::keyCache('_relatedCourses_' . md5(json_encode($courseIDs)));
        $courses = Cache::tags(Course::cacheTag())->remember(
            $cacheKey,
            now()->addMinutes(30),
            function () use ($courseIDs, $now) {
                return Course::query()
                    ->whereIn('ID', $courseIDs)
                    ->with([
                        'teacher' => function ($query) {
                            $query->select('ID', 'name', 'family', 'avatarSID')->with('storage');
                        },
                        'courseIntro' => function ($query) {
                            $query->where('type', CourseIntroTypes::Banner->value)->with('storage');
                        },
                    ])
                    ->where('startDate', '<', $now)
                    ->get();
            }
        );

        return $this->success([
            'courses' => RelatedCourseResource::collection($courses),
            'hasNextPage' => $hasNextPage,
        ]);
    }

    /**
     * @param ContentRequest $request
     * @return JsonResponse
     * @link https://docs.google.com/document/d/1H5NvkkT9a6tGe5jk8TFkvXqlUDJFdHNkHjdI_5UtYyo/edit?tab=t.0
     */
    public function episodeContent(ContentRequest $request)
    {
        $data = $request->validated();

        // Get Episode
        $cacheKey = SectionEpisode::keyCache('_episodeContent_' . $data['episodeContentID']);
        $episode = Cache::tags(SectionEpisode::cacheTag())->remember(
            $cacheKey,
            now()->addMinutes(15),
            function () use ($data) {
                return SectionEpisode::query()
                    ->where('episodeContentID', $data['episodeContentID'])
                    ->where('status', EpisodeStatuses::Published->value)
                    ->first();
            }
        );
        if (!$episode) {
            return $this->error(1, st('Episode not found'));
        }

        // Get content
        $cacheKey = EpisodeContent::keyCache('_' . $data['episodeContentID']);
        $content = Cache::tags(EpisodeContent::cacheTag())->remember(
            $cacheKey,
            now()->addMinutes(30),
            function () use ($data) {
                return EpisodeContent::query()
                    ->where('ID', $data['episodeContentID'])
                    ->first();
            }
        );
        if (!$content) {
            return $this->error(2, st('record not found'));
        }

        return $this->success([
            'episodeContent' => EpisodeContentResource::make($content),
        ]);
    }

    /**
     * @param JoinFreeRequest $request
     * @return JsonResponse
     */
    public function joinFree(JoinFreeRequest $request)
    {
        $user = Auth::user();
        $courseID = $request->validated()['courseID'];
        $now = time();

        $course = Course::query()
            ->where('ID', $courseID)
            ->where('price', 0)
            ->where('startDate', '<', $now)
            ->first();

        if (!$course) {
            return $this->error(1, st('The course was not found or is not free.'));
        }

        $alreadyJoined = UserCourse::where('courseID', $courseID)
            ->where('userID', $user->ID)
            ->exists();

        if ($alreadyJoined) {
            return $this->error(2, st('You have already joined this course.'));
        }

        if ($course->participantLimitation > 0 && $course->participants >= $course->participantLimitation) {
            return $this->error(3, st('The course capacity has been reached.'));
        }

        $course->increment('participants');

        UserCourse::create([
            'courseID' => $courseID,
            'userID' => $user->ID,
            'status' => 'active',
        ]);

        Notification::send(
            $user->ID,
            st('Notif - Course - free join title'),
            st('Notif - Course - free join content', ['name' => $user->name, 'course' => $course->name])
        );

        return $this->success();
    }

    /**
     * @return JsonResponse
     * @link https://docs.google.com/document/d/1cJyR2Po7kHITmEvwb-FNHUf3fN50--YErGeU1S3RMlE/edit?tab=t.0
     */
    public function listPurchased(PurchasedListRequest $request)
    {
        $data = $request->validated();
        $userID = Auth::user()->ID;

        $purchasedCourseIDs = UserCourse::query()
            ->where('userID', $userID)
            ->pluck('courseID')
            ->toArray();

        $courses = Course::query()
            ->select(
                'ID',
                'name',
                'duration',
                'type',
                'status',
                'slug',
                'level',
                'teacherID',
            )
            ->whereIn('ID', $purchasedCourseIDs)
            ->with([
                'courseIntro' => function ($query) {
                    $query->where('type', CourseIntroTypes::Banner->value)->with('storage');
                },
                'teacher' => function ($query) {
                    $query->select('ID', 'name', 'family', 'avatarSID')->with('storage');
                },
            ])
            ->pageLimit($data['page'] ?? null, $data['itemsPerPage'] ?? null);

        return $this->success([
            'courses' => PurchasedCourseResource::collection($courses),
        ]);
    }
}

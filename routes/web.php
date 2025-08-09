<?php

use App\Http\Controllers\Admin\AssignmentController;
use App\Http\Controllers\Admin\ChildGame\MovieCategoryController;
use App\Http\Controllers\Admin\ChildGame\MovieController;
use App\Http\Controllers\Admin\ChildGame\MovieSeasonController;
use App\Http\Controllers\Admin\ChildGame\SeasonEpisodeController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\CourseCategoryController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\CourseSectionController;
use App\Http\Controllers\Admin\EpisodeContentController;
use App\Http\Controllers\Admin\ExamController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\SectionEpisodeController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserAssignmentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return env('APP_NAME', 'mojahaz');
});

Route::group(['prefix' => 'admin', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::group(['prefix' => 'course'], function () {
        Route::get('/list', [CourseController::class, 'list'])->name('admin.course.list');
        Route::get('/create', [CourseController::class, 'create'])->name('admin.course.create');
        Route::post('/store', [CourseController::class, 'store'])->name('admin.course.store');
        Route::get('/edit/{course}', [CourseController::class, 'edit'])->name('admin.course.edit');
        Route::post('/update/{course}', [CourseController::class, 'update'])->name('admin.course.update');
        Route::group(['prefix' => 'section'], function () {
            Route::get('/list', [CourseSectionController::class, 'list'])->name('admin.course.section.list');
            Route::get('/create/{course}', [CourseSectionController::class, 'create'])->name(
                'admin.course.section.create'
            );
            Route::post('/store/{course}', [CourseSectionController::class, 'store'])->name(
                'admin.course.section.store'
            );
            Route::get('/edit/{courseSection}', [CourseSectionController::class, 'edit'])->name(
                'admin.course.section.edit'
            );
            Route::post('/update/{courseSection}', [CourseSectionController::class, 'update'])->name(
                'admin.course.section.update'
            );
            Route::group(['prefix' => 'episode'], function () {
                Route::get('/list', [SectionEpisodeController::class, 'list'])->name(
                    'admin.course.section.episode.list'
                );
                Route::get('/create/{courseSection}', [SectionEpisodeController::class, 'create'])->name(
                    'admin.course.section.episode.create'
                );
                Route::post('/store/{courseSection}', [SectionEpisodeController::class, 'store'])->name(
                    'admin.course.section.episode.store'
                );
                Route::get('/edit/{sectionEpisode}', [SectionEpisodeController::class, 'edit'])->name(
                    'admin.course.section.episode.edit'
                );
                Route::post('/update/{sectionEpisode}', [SectionEpisodeController::class, 'update'])->name(
                    'admin.course.section.episode.update'
                );
                Route::group(['prefix' => 'content'], function () {
                    Route::get('/list', [EpisodeContentController::class, 'list'])->name(
                        'admin.course.section.episode.content.list'
                    );
                    Route::get('/create/{sectionEpisode}', [EpisodeContentController::class, 'create'])->name(
                        'admin.course.section.episode.content.create'
                    );
                    Route::post('/store/{sectionEpisode}', [EpisodeContentController::class, 'store'])->name(
                        'admin.course.section.episode.content.store'
                    );
                    Route::get('/edit/{episodeContent}', [EpisodeContentController::class, 'edit'])->name(
                        'admin.course.section.episode.content.edit'
                    );
                    Route::post('/update/{episodeContent}', [EpisodeContentController::class, 'update'])->name(
                        'admin.course.section.episode.content.update'
                    );
                });
            });
        });
    });
    Route::group(['prefix' => 'courseCategory'], function () {
        Route::get('/list', [CourseCategoryController::class, 'list'])->name('admin.courseCategory.list');
        Route::get('/create', [CourseCategoryController::class, 'create'])->name('admin.courseCategory.create');
        Route::post('/store', [CourseCategoryController::class, 'store'])->name('admin.courseCategory.store');
        Route::get('/edit/{courseCategory}', [CourseCategoryController::class, 'edit'])->name(
            'admin.courseCategory.edit'
        );
        Route::post('/update/{courseCategory}', [CourseCategoryController::class, 'update'])->name(
            'admin.courseCategory.update'
        );
    });
    Route::group(['prefix' => 'question'], function () {
        Route::get('/list', [QuestionController::class, 'list'])->name('admin.question.list');

        Route::get('/create/{exam}', [QuestionController::class, 'create'])->name('admin.question.create');
        Route::post('/store', [QuestionController::class, 'store'])->name('admin.question.store');

        Route::get('/edit/{question}', [QuestionController::class, 'edit'])->name('admin.question.edit');
        Route::post('/update/{question}', [QuestionController::class, 'update'])->name('admin.question.update');
    });
    Route::group(['prefix' => 'exam'], function () {
        Route::get('/create/{sectionEpisode}', [ExamController::class, 'create'])->name('admin.exam.create');
        Route::post('/store/{sectionEpisode}', [ExamController::class, 'store'])->name('admin.exam.store');

        Route::get('/edit/{exam}', [ExamController::class, 'edit'])->name('admin.exam.edit');
        Route::post('/update/{exam}', [ExamController::class, 'update'])->name('admin.exam.update');

        Route::get('/list', [ExamController::class, 'list'])->name('admin.exam.list');

        Route::get('/attendees', [ExamController::class, 'attendees'])->name('admin.exam.attendees');
        Route::get('/answerSheet/{userExam}', [ExamController::class, 'answerSheet'])->name('admin.exam.answerSheet');
    });
    Route::group(['prefix' => 'user'], function () {
        Route::group(['prefix' => 'parent'], function () {
            Route::get('/list', [UserController::class, 'listParent'])->name('admin.user.parent.list');
            Route::get('/show/{parent}', [UserController::class, 'showParent'])->name('admin.user.parent.show');

            Route::get('/create', [UserController::class, 'createParent'])->name('admin.user.parent.create');
            Route::post('/store', [UserController::class, 'storeParent'])->name('admin.user.parent.store');

            Route::get('/edit/{parent}', [UserController::class, 'editParent'])->name('admin.user.parent.edit');
            Route::post('/update/{user}', [UserController::class, 'updateParent'])->name(
                'admin.user.parent.update',
            );
        });

        Route::group(['prefix' => 'child'], function () {
            Route::get('/list', [UserController::class, 'listChild'])->name('admin.user.child.list');
            Route::get('/show/{child}', [UserController::class, 'showChild'])->name('admin.user.child.show');

            Route::get('/create', [UserController::class, 'createChild'])->name('admin.user.child.create');
            Route::post('/store', [UserController::class, 'storeChild'])->name('admin.user.child.store');

            Route::get('/edit/{child}', [UserController::class, 'editChild'])->name('admin.user.child.edit');
            Route::post('/update/{user}', [UserController::class, 'updateChild'])->name('admin.user.child.update');
        });

        Route::get('/delete/{user}', [UserController::class, 'delete'])->name('admin.user.delete');
    });
    Route::group(['prefix' => 'assignment'], function () {
        Route::get('/list', [AssignmentController::class, 'list'])->name('admin.assignment.list');
        Route::get('/create/{sectionEpisode}', [AssignmentController::class, 'create'])->name(
            'admin.assignment.create'
        );
        Route::post('/store/{sectionEpisode}', [AssignmentController::class, 'store'])->name('admin.assignment.store');
        Route::get('/edit/{assignment}', [AssignmentController::class, 'edit'])->name('admin.assignment.edit');
        Route::post('/update/{assignment}', [AssignmentController::class, 'update'])->name('admin.assignment.update');
        Route::group(['prefix' => 'check'], function () {
            Route::get('/list', [UserAssignmentController::class, 'list'])->name(
                'admin.assignment.check.list'
            );
            Route::get('/show/{userAssignment}', [UserAssignmentController::class, 'show'])->name(
                'admin.assignment.check.show'
            );
            Route::post('/update/{userAssignment}', [UserAssignmentController::class, 'update'])->name(
                'admin.assignment.check.update'
            );
        });
    });
    Route::group(['prefix' => 'comment'], function () {
        Route::get('/list', [CommentController::class, 'list'])->name('admin.comment.list');
        Route::get('/edit/{comment}', [CommentController::class, 'edit'])->name('admin.comment.edit');
        Route::post('/update/{comment}', [CommentController::class, 'update'])->name('admin.comment.update');
        Route::get('/create/{comment}', [CommentController::class, 'create'])->name('admin.comment.create');
        Route::post('/store/{comment}', [CommentController::class, 'store'])->name('admin.comment.store');
    });

    Route::group(['prefix' => 'setting'], function () {
        Route::group(['prefix' => 'indexPage'], function () {
            Route::get('/', [SettingController::class, 'indexPage'])->name('admin.setting.indexPage');
            // Banners
            Route::get('/banners/show', [SettingController::class, 'homeBannersShow'])->name(
                'admin.setting.indexPage.homeBanners.show'
            );
            Route::post('/banners/set', [SettingController::class, 'homeBannersSet'])->name(
                'admin.setting.indexPage.homeBanners.set'
            );
            // homeContent
            Route::get('/homeContent/show', [SettingController::class, 'homeContent'])->name(
                'admin.setting.indexPage.homeContent.show'
            );
            Route::post('/homeContent/set', [SettingController::class, 'homeContentSet'])->name(
                'admin.setting.indexPage.homeContent.set'
            );
            // Latest articles
            Route::get('/latestArticles/show', [SettingController::class, 'latestArticlesShow'])->name(
                'admin.setting.indexPage.homeLatestArticles.show'
            );
            Route::post('/latestArticles/set', [SettingController::class, 'latestArticlesSet'])->name(
                'admin.setting.indexPage.homeLatestArticles.set'
            );
            // Latest Courses
            Route::get('/latestCourses/show', [SettingController::class, 'latestCoursesShow'])->name(
                'admin.setting.indexPage.homeLatestCourses.show'
            );
            Route::post('/latestCourses/set', [SettingController::class, 'latestCoursesSet'])->name(
                'admin.setting.indexPage.homeLatestCourses.set'
            );

            // logo
            Route::get('/logo/show', [SettingController::class, 'logo'])->name(
                'admin.setting.logo.show'
            );
            Route::post('/logo/set', [SettingController::class, 'logoSet'])->name(
                'admin.setting.logo.set'
            );
        });
        Route::get('/keyMSGWAY', [SettingController::class, 'keyMSGWAY'])->name('admin.setting.keyMSGWAY');
        Route::post('/setKeyMSGWAY', [SettingController::class, 'setKeyMSGWAY'])->name('admin.setting.setKeyMSGWAY');
    });

    Route::group(['prefix' => 'cg'], function () {
        Route::group(['prefix' => 'movieCategory'], function () {
            Route::get('/list', [MovieCategoryController::class, 'list'])->name('admin.cg.movieCategory.list');
            Route::get('/create', [MovieCategoryController::class, 'create'])->name('admin.cg.movieCategory.create');
            Route::post('/store', [MovieCategoryController::class, 'store'])->name('admin.cg.movieCategory.store');
            Route::get('/edit/{movieCategory}', [MovieCategoryController::class, 'edit'])->name(
                'admin.cg.movieCategory.edit'
            );
            Route::post('/update/{movieCategory}', [MovieCategoryController::class, 'update'])->name(
                'admin.cg.movieCategory.update'
            );
            Route::get('/archive/{movieCategory}', [MovieCategoryController::class, 'archive'])->name(
                'admin.cg.movieCategory.archive'
            );
            Route::get('/unarchive/{movieCategory}', [MovieCategoryController::class, 'unarchive'])->name(
                'admin.cg.movieCategory.unarchive'
            );
        });
        Route::group(['prefix' => 'movie'], function () {
            Route::get('/list', [MovieController::class, 'list'])->name('admin.cg.movie.list');
            Route::get('/create', [MovieController::class, 'create'])->name('admin.cg.movie.create');
            Route::post('/store', [MovieController::class, 'store'])->name('admin.cg.movie.store');
            Route::get('/edit/{movie}', [MovieController::class, 'edit'])->name('admin.cg.movie.edit');
            Route::post('/update/{movie}', [MovieController::class, 'update'])->name('admin.cg.movie.update');
            Route::get('/archive/{movie}', [MovieController::class, 'archive'])->name('admin.cg.movie.archive');
            Route::get('/unarchive/{movie}', [MovieController::class, 'unarchive'])->name('admin.cg.movie.unarchive');
        });
        Route::group(['prefix' => 'movieSeason'], function () {
            Route::get('/list', [MovieSeasonController::class, 'list'])->name('admin.cg.movieSeason.list');
            Route::get('/create/{movie}', [MovieSeasonController::class, 'create'])->name('admin.cg.movieSeason.create');
            Route::post('/store/{movie}', [MovieSeasonController::class, 'store'])->name('admin.cg.movieSeason.store');
            Route::get('/edit/{movieSeason}', [MovieSeasonController::class, 'edit'])->name('admin.cg.movieSeason.edit');
            Route::post('/update/{movieSeason}', [MovieSeasonController::class, 'update'])->name('admin.cg.movieSeason.update');
            Route::get('/archive/{movieSeason}', [MovieSeasonController::class, 'archive'])->name('admin.cg.movieSeason.archive');
            Route::get('/unarchive/{movieSeason}', [MovieSeasonController::class, 'unarchive'])->name('admin.cg.movieSeason.unarchive');
        });
        Route::group(['prefix' => 'seasonEpisode'], function () {
            Route::get('/list', [SeasonEpisodeController::class, 'list'])->name('admin.cg.seasonEpisode.list');
            Route::get('/create', [SeasonEpisodeController::class, 'create'])->name('admin.cg.seasonEpisode.create');
            Route::post('/store', [SeasonEpisodeController::class, 'store'])->name('admin.cg.seasonEpisode.store');
            Route::get('/edit/{seasonEpisode}', [SeasonEpisodeController::class, 'edit'])->name('admin.cg.seasonEpisode.edit');
            Route::post('/update/{seasonEpisode}', [SeasonEpisodeController::class, 'update'])->name('admin.cg.seasonEpisode.update');
            Route::get('/delete/{seasonEpisode}', [SeasonEpisodeController::class, 'delete'])->name('admin.cg.seasonEpisode.delete');
        });
    });
});

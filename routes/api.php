<?php

use App\Http\Controllers\Api\AssignmentController;
use App\Http\Controllers\Api\CinemaController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\ExamController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\SupportController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\OrderController;
use Illuminate\Support\Facades\Route;

Route::prefix('/course')->controller(CourseController::class)->group(function () {
    Route::post('/category/list', 'categoryList')->name('course.category.list');
    Route::post('/list', 'list')->name('course.list');
    Route::post('/get', 'get')->name('course.get');
    Route::post('/related', 'related')->name('course.related');
    Route::post('/episode/get', 'episodeGet')->name('course.episode.get');
    Route::post('/episode/content', 'episodeContent')->name('course.episode.content');
});

Route::prefix('/setting')->controller(SettingController::class)->group(function () {
    Route::post('/indexPage', 'indexPage')->name('setting.indexPage');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('/user')->controller(UserController::class)->group(function () {
        Route::prefix('/profile')->group(function () {
            Route::post('/set', 'setProfile')->name('user.profile.set');
            Route::post('/get', 'getProfile')->name('user.profile.get');
            Route::post('/update', 'updateProfile')->name('user.profile.update');
        });
//        Route::prefix('/child')->group(function () {
//            Route::post('/create', 'createChild')->name('user.child.create');
//            Route::post('/list', 'listChild')->name('user.child.list');
//            Route::post('/get', 'getChild')->name('user.child.get');
//            Route::post('/delete', 'deleteChild')->name('user.child.delete');
//            Route::post('/update', 'updateChild')->name('user.child.update');
//        });
//        Route::post('/switch/child', 'switchChild')->name('user.switch.Child');
//    });
//    Route::prefix('/support')->controller(SupportController::class)->group(function () {
//        Route::post('/departments', 'departments')->name('support.departments');
//        Route::post('/tickets', 'tickets')->name('support.tickets');
//        Route::post('/tickets/send', 'send')->name('support.send');
//        Route::post('/tickets/reply', 'reply')->name('support.reply');
//        Route::post('/tickets/get', 'get')->name('support.get');
//        Route::post('/tickets/close', 'close')->name('support.close');
    });
    Route::prefix('/course')->controller(CourseController::class)->group(function () {
        Route::post('/joinFree', 'joinFree')->name('course.joinFree');
        Route::post('/list/purchased', 'listPurchased')->name('course.list.purchased');
    });
    Route::prefix('/exam')->controller(ExamController::class)->group(function () {
        Route::post('/get', 'get')->name('exam.get');
        Route::post('/join', 'join')->name('exam.join');
        Route::post('/question', 'question')->name('exam.question');
        Route::post('/answer', 'answer')->name('exam.answer');
        Route::post('/result', 'result')->name('exam.result');
    });
    Route::prefix('/comment')->controller(CommentController::class)->group(function () {
        Route::post('/list', 'list')->name('comment.list');
        Route::post('/send', 'send')->name('comment.send');
    });
    Route::prefix('/notification')->controller(NotificationController::class)->group(function () {
        Route::post('/list', 'list')->name('notification.list');
        Route::post('/read', 'read')->name('notification.read');
    });
    Route::prefix('/assignment')->controller(AssignmentController::class)->group(function () {
        Route::post('/get', 'get')->name('assignment.get');
        Route::post('/receive', 'receive')->name('assignment.receive');
        Route::post('/send', 'send')->name('assignment.send');
    });

    Route::prefix('order')->controller(OrderController::class)->group(function () {
        Route::post('/item/add', 'itemAdd')->name('order.item.add');
        Route::post('/item/remove', 'itemRemove')->name('order.item.remove');
        Route::post('/item/list', 'itemList')->name('order.item.list');
    });
});

//Route::prefix('/cinema')->controller(CinemaController::class)->group(function () {
//    Route::post('/index', 'index')->name('cinema.index');
//});

//Route::middleware('auth:children')->group(function () {
//    //
//});


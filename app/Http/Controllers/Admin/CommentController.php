<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\CommentDatatable;
use App\Enums\CommentStatuses;
use App\Enums\UserTypes;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Comment\CreateRequest;
use App\Http\Requests\Admin\Comment\UpdateRequest;
use App\Models\Comment;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Keyhanweb\Subsystem\Enums\UserStatus;
use Keyhanweb\Subsystem\Models\Manager;

class CommentController extends Controller
{
    /**
     * @param CommentDatatable $datatable
     * @return mixed
     */
    public function list(CommentDatatable $datatable)
    {
        $statuses = CommentStatuses::valuesTranslate();
        $users = User::query()
            ->select(
                'ID',
                'name',
                'family'
            )
            ->where('status', UserStatus::Active->value)
            ->where('type', UserTypes::Parent->value)
            ->get()
            ->pluck('fullname', 'ID');

        return $datatable->render('admin.comment.list', compact('statuses', 'users'));
    }

    /**
     * @param Comment $comment
     * @return View
     */
    public function edit(Comment $comment)
    {
        $statuses = CommentStatuses::valuesTranslate();
        $userName = '';
        $managerName = '';

        if (!empty($comment->managerID)) {
            $manager = Manager::query()
                ->where('id', $comment->managerID)
                ->firstOrFail();
            $managerName = $manager->name . ' ' . $manager->family;
        } else {
            $user = User::query()
                ->where('id', $comment->userID)
                ->firstOrFail();
            $userName = $user->fullName;
        }

        return view('admin.comment.edit', compact('comment', 'managerName', 'userName', 'statuses'));
    }

    /**
     * @param UpdateRequest $request
     * @param Comment $comment
     * @return RedirectResponse
     */
    public function update(UpdateRequest $request, Comment $comment)
    {
        $data = $request->validated();
        $parentComment = [];

        if ($comment->parentID) {
            $parentComment = Comment::query()
                ->where('status', CommentStatuses::APPROVED->value)
                ->where('ID', $comment->parentID)
                ->first();
            if (!$parentComment) {
                return back()->withErrors(st('parent comment not found'));
            }
        }

        $comment->update([
            'status' => $data['status'],
            'content' => strip_tags($data['content']),
        ]);

        if ($comment->parentID) {
            $parentComment->loadCount([
                'replies as repliesCount' => function ($query) {
                    $query->where('status', CommentStatuses::APPROVED->value);
                }
            ]);
            if ($parentComment->repliesCount == 1) {
                $parentComment->hasReply = 1;
                $parentComment->save();
            } elseif ($parentComment->repliesCount == 0) {
                $parentComment->hasReply = 0;
                $parentComment->save();
            }
        }
        return redirect()->route('admin.comment.list')->with('success', st('Operation done successfully'));
    }

    /**
     * @param CreateRequest $request
     * @param Comment $comment
     * @return RedirectResponse
     */
    public function store(CreateRequest $request, Comment $comment)
    {
        $data = $request->validated();

        Comment::create([
            'commentable_type' => $comment->commentable_type,
            'commentable_id' => $comment->commentable_id,
            'parentID' => $comment->ID,
            'managerID' => auth()->id(),
            'status' => CommentStatuses::APPROVED,
            'content' => strip_tags($data['content']),
        ]);

        $comment->hasReply = true;
        $comment->save();

        $modelInstance = new $comment->commentable_type();
        $modelObject = $modelInstance::find($comment->commentable_id);

        $commentedTitle = match (true) {
            str_ends_with($comment->commentable_type, 'Course') => st('course') . ' ' . $modelObject->name,
            str_ends_with($comment->commentable_type, 'Article') => st('article') . ' ' . $modelObject->title,
            // Todo: we should add products at here too.
        };

        $user = User::find($comment->userID);
        Notification::send(
            $user->ID,
            st('Notif - Comment - replying title'),
            st('Notif - Comment - admin replying content', ['name' => $user->name, 'title' => $commentedTitle])
        );

        return redirect()->route('admin.comment.list')->with('success', st('Operation done successfully'));
    }

    /**
     * @param Comment $comment
     * @return View|RedirectResponse
     */
    public function create(Comment $comment)
    {
        return view('admin.comment.create', compact('comment'));
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Enums\CommentStatuses;
use App\Http\Requests\Api\Comment\ListRequest;
use App\Http\Requests\Api\Comment\SendRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class CommentController extends ApiController
{
    /**
     * @param ListRequest $request
     * @return JsonResponse
     * @link https://docs.google.com/document/d/1WTpP9jNar0P3qmrur9SRduXWvf3GojQazvRfBmdHYoE/edit?tab=t.0
     */
    public function list(ListRequest $request)
    {
        $data = $request->validated();

        $namespaces = [
            'App\\Models\\',
            'Keyhanweb\\Subsystem\\Models\\',
        ];

        $commentableModelPath = null;
        foreach ($namespaces as $namespace) {
            $candidate = $namespace . $data['commentableType'];
            if (class_exists($candidate)) {
                $commentableModelPath = $candidate;
                break;
            }
        }
        $commentableModel = new $commentableModelPath();

        $existCheckQuery = $commentableModel::query()
            ->where('ID', $data['commentableID'])
            ->exists();
        if (!$existCheckQuery) {
            return $this->error(1, st('record not found'));
        }

        $keyCache = Comment::keyCache(
            '_' . md5($data['commentableType'] . $data['commentableID']) . ($data['page'] ?? '1')
        );
        $comments = Cache::tags(Comment::cacheTag())->remember($keyCache, now()->addMinutes(15), function () use ($commentableModelPath, $data) {
            return Comment::query()
                ->select(
                    'ID',
                    'commentable_type',
                    'commentable_id',
                    'userID',
                    'managerID',
                    'parentID',
                    'content',
                    'hasReply',
                    'created',
                )
                ->with(
                    'replies',
                    'authorUser:ID,name,family',
                    'authorManager:id,name,family',
                    'authorUser.storage'
                )
                ->whereNull('parentID')
                ->where('status', CommentStatuses::APPROVED->value)
                ->where('commentable_type', $commentableModelPath)
                ->where('commentable_id', $data['commentableID'])
                ->orderByDesc('created')
                ->pageLimit($data['page'] ?? null, $data['itemPerPage'] ?? 5);
        });

        return $this->success([
            'comments' => CommentResource::collection($comments),
            'hasNextPage' => $comments->hasNextPage,
        ]);
    }

    /**
     * @param SendRequest $request
     * @return JsonResponse
     * @link https://docs.google.com/document/d/1Hg8tczNxkWrZLcsw0uFhbsUryWvKT75c1X5EY4RcS10/edit?tab=t.0
     */
    public function send(SendRequest $request)
    {
        $data = $request->validated();
        $content = htmlspecialchars(strip_tags($data['content']), ENT_QUOTES, 'UTF-8'); // Prevent XSS

        $namespaces = [
            'App\\Models\\',
            'Keyhanweb\\Subsystem\\Models\\',
        ];

        $commentableModelPath = null;
        foreach ($namespaces as $namespace) {
            $candidate = $namespace . $data['commentableType'];
            if (class_exists($candidate)) {
                $commentableModelPath = $candidate;
                break;
            }
        }
        $commentableModel = new $commentableModelPath();

        $existCheckQuery = $commentableModel::query()
            ->where('ID', $data['commentableID'])
            ->exists();
        if (!$existCheckQuery) {
            return $this->error(1, st('record not found'));
        }

        if (!empty($data['parentID'])) {
            $parentComment = Comment::query()
                ->where('status', CommentStatuses::APPROVED->value)
                ->where('ID', $data['parentID'])
                ->exists();
            if (!$parentComment) {
                return $this->error(2, st('parent comment not found'));
            }
        }

        Comment::create([
            'commentable_type' => $commentableModelPath,
            'commentable_id' => $data['commentableID'],
            'userID' => Auth::id(),
            'parentID' => $data['parentID'] ?? null,
            'content' => $content, // Prevent SQLi
        ]);

        return $this->success();
    }
}

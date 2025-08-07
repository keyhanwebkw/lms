<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\Notification\ListRequest;
use App\Http\Requests\Api\Notification\ReadRequest;
use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class NotificationController extends ApiController
{
    /**
     * @return JsonResponse
     * @link https://docs.google.com/document/d/1iyDKYgwgKwbw-8UEay6cnZoWF8zMDAWRSKnPYuazm3c/edit?tab=t.0
     */

    public function list(ListRequest $request)
    {
        $data = $request->validated();
        $userID = Auth::user()->ID;

        $cacheKey = Notification::keyCache('_' . $userID . '_' . md5(json_encode(array_filter($data))));
        $notifications = Cache::tags(Notification::cacheTag($userID))->remember(
            $cacheKey,
            now()->addMinutes(60),
            function () use ($userID, $data) {
                return Notification::query()
                    ->select([
                        'ID',
                        'userID',
                        'title',
                        'content',
                        'type',
                        'created',
                        'read',
                    ])
                    ->where('userID', $userID)
                    ->orderBy('read')
                    ->orderBy('created', 'desc')
                    ->pageLimit(
                        $data['page'] ?? 1,
                        $data['itemsPerPage'] ?? 15
                    );
            }
        );

        return $this->success([
            'notifications' => NotificationResource::collection($notifications),
            'hasNextPage' => $notifications->hasNextPage,
        ]);
    }

    /**
     * @return JsonResponse
     * @link https://docs.google.com/document/d/1cKo0sTGuIKrUZWlO9dKySrTnktwa0NMyeatH56JonyI/edit?tab=t.0
     */
    public function read(ReadRequest $request)
    {
        $data = $request->validated();

        Notification::markAsReadByIDs($data['IDs']);

        return $this->success();
    }
}

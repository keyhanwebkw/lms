<?php

namespace App\Http\Controllers\Api;

use App\Enums\EpisodeStatuses;
use App\Enums\UserAssignmentStatuses;
use App\Http\Requests\Api\Assignment\GetRequest;
use App\Http\Requests\Api\Assignment\ReceiveRequest;
use App\Http\Requests\Api\Assignment\SendRequest;
use App\Http\Resources\AssignmentContentResource;
use App\Http\Resources\AssignmentReceiveResource;
use App\Http\Resources\AssignmentSummaryResource;
use App\Http\Resources\UserAssignmentResource;
use App\Models\Assignment;
use App\Models\AssignmentContent;
use App\Models\SectionEpisode;
use App\Models\UserAssignment;
use App\Traits\ValidatesContentFile;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Keyhanweb\Subsystem\Models\Storage;

class AssignmentController extends ApiController
{
    use ValidatesContentFile;

    // ----------------- Errors -----------------
    private int $ASSIGNMENT_NOT_FOUND1 = 1;
    private int $ASSIGNMENT_NOT_FOUND2 = 2;
    private int $USER_ASSIGNMENT_NOT_FOUND = 3;
    private int $ASSIGNMENT_NOT_FOUND3 = 4;
    private int $RETRY_LIMIT_REACHED = 5;
    private int $NOT_ALLOWED_TO_SEND = 6;
    private int $DEADLINE_HAS_PASSED = 7;
    private int $EPISODE_NOT_FOUND1 = 8;
    private int $EPISODE_NOT_FOUND2 = 9;

    // ---------------- Checker ----------------

    private function episodeAssignmentPermission(SectionEpisode $episode): array
    {
        $isAllowedToReceive = true;
        $errorCode = null;
        $errorMessage = null;

        // Logic

        return [
            'isAllowedToReceive' => $isAllowedToReceive,
            'errorCode' => $errorCode,
            'errorMessage' => $errorMessage,
        ];
    }

    // ----------------- Logic -----------------

    /**
     * @param GetRequest $request
     * @return JsonResponse
     * @link https://docs.google.com/document/d/1ttwAGV00Gt9D6LLdKHCt5SkMknNwzMLcSizR3xF2seE/edit?tab=t.0
     */
    public function get(GetRequest $request)
    {
        $data = $request->validated();
        $isAllowedToSend = true;

        // Get the assignment
        $cacheKey = Assignment::keyCache('_' . $data['assignmentID']);
        $assignment = Cache::tags(Assignment::cacheTag())->remember(
            $cacheKey,
            now()->addMinutes(30),
            function () use ($data) {
                return Assignment::query()
                    ->where('ID', $data['assignmentID'])
                    ->first();
            }
        );
        if (!$assignment) {
            return $this->error($this->ASSIGNMENT_NOT_FOUND1, st('Assignment not found'));
        }

        // Get Episode for permission checking
        $cacheKey = SectionEpisode::keyCache('_assignment_' . $data['assignmentID']);
        $episode = Cache::tags(SectionEpisode::cacheTag())->remember($cacheKey, now()->addMinutes(15), function () use ($data) {
            return SectionEpisode::query()
                ->where('assignmentID', $data['assignmentID'])
                ->where('status', EpisodeStatuses::Published->value)
                ->first();
        });

        if (!$episode) {
            return $this->error($this->EPISODE_NOT_FOUND1, st('Episode not found'));
        }

        $receivePermission = $this->episodeAssignmentPermission($episode);

        // Get the user assignment (manager's response to assignment will receive from it)
        $userID = Auth::user()->ID;
        $userAssignment = UserAssignment::query()
            ->where('assignmentID', $data['assignmentID'])
            ->where('userID', $userID)
            ->first();
        $haveReceivedBefore = (bool)$userAssignment;

        // Check that user have been received the assignment before and retry counts
        if ($haveReceivedBefore) {
            // There is 2 status that should be able to send:
            // 1: inProgress (user is trying for first time)
            // 2: rejected (user rejected once and now should try another change)
            $ableToSendStatuses = [UserAssignmentStatuses::InProgress->value, UserAssignmentStatuses::Rejected->value];
            // If user have not reached the limit and user's assignment has the able statuses, will allow to send again
            $isAllowedToSend = ($userAssignment->retryCount < $assignment->retryCount &&
                in_array($userAssignment->status, $ableToSendStatuses));

            // Get content which user have sent
            $assignmentContent = AssignmentContent::query()
                ->where('userAssignmentID', $userAssignment->ID)
                ->get();
        }

        // There is 2 possible situation:
        // 1 - assignment has not any response yet.
        // 2 - assignment has response, but it has resubmitted. so we should not show previous response!
        $shouldShowManagerResponse = ($userAssignment?->managerResponse &&
            $userAssignment?->status !== UserAssignmentStatuses::Resubmitted->value);

        return $this->success([
            'assignment' => AssignmentSummaryResource::make($assignment),
            'userContent' => isset($assignmentContent) ? AssignmentContentResource::collection(
                $assignmentContent
            ) : null,
            'managerResponse' => $shouldShowManagerResponse ? UserAssignmentResource::make($userAssignment) : null,
            'receivePermission' => $receivePermission,
            'submissionDeadline' => $haveReceivedBefore ? $userAssignment->created + ($assignment->deadline * 3600)
                : null, // Hour to seconds
            'status' => $haveReceivedBefore ? $userAssignment->status : 'todo',
            'isAllowedToSend' => ($haveReceivedBefore && $receivePermission['isAllowedToReceive']) ? $isAllowedToSend : false,
            'userAssignmentID' => $userAssignment?->ID,
        ]);
    }

    /**
     * @param ReceiveRequest $request
     * @return JsonResponse
     * @link https://docs.google.com/document/d/1tWMo1HvsNOMdSkK4zKQSXvhFMFDKrDmaFstXkSbSDO0/edit?tab=t.0
     */
    public function receive(ReceiveRequest $request)
    {
        $data = $request->validated();

        // Get the assignment
        $cacheKey = Assignment::keyCache('_' . $data['assignmentID']);
        $assignment = Cache::tags(Assignment::cacheTag())->remember(
            $cacheKey,
            now()->addMinutes(30),
            function () use ($data) {
                return Assignment::query()
                    ->where('ID', $data['assignmentID'])
                    ->first();
            }
        );
        if (!$assignment) {
            return $this->error($this->ASSIGNMENT_NOT_FOUND2, st('Assignment not found'));
        }

        // Get Episode for permission checking
        $cacheKey = SectionEpisode::keyCache('_assignment_' . $data['assignmentID']);
        $episode = Cache::tags(SectionEpisode::cacheTag())->remember($cacheKey, now()->addMinutes(15), function () use ($data) {
            return SectionEpisode::query()
                ->where('assignmentID', $data['assignmentID'])
                ->where('status', EpisodeStatuses::Published->value)
                ->first();
        });
        if (!$episode) {
            return $this->error($this->EPISODE_NOT_FOUND2, st('Episode not found'));
        }

        $receivePermission = $this->episodeAssignmentPermission($episode);
        if (!$receivePermission['isAllowedToReceive']) {
            return $this->error($receivePermission['errorCode'], $receivePermission['errorMessage']);
        }

        $userID = Auth::user()->ID;
        $userAssignment = UserAssignment::query()
            ->where('assignmentID', $data['assignmentID'])
            ->where('userID', $userID)
            ->first();
        $haveReceivedBefore = (bool)$userAssignment;

        // If user have not received before, we will create a record to know the start time for deadline calculations
        if (!$haveReceivedBefore) {
            $userAssignment = UserAssignment::query()
                ->create([
                    'assignmentID' => $data['assignmentID'],
                    'userID' => $userID,
                ]);
        }

        return $this->success([
            'assignment' => AssignmentReceiveResource::make($assignment),
            'submissionDeadline' => $userAssignment->created + ($assignment->deadline * 3600), // Hour to seconds
        ]);
    }

    /**
     * @param SendRequest $request
     * @return JsonResponse
     * @link https://docs.google.com/document/d/1Seg1Z1__PvdCC09j3-tmQ97o0tQLIwimyYXL_s5hL4s/edit?tab=t.0
     */
    public function send(SendRequest $request)
    {
        $data = $request->validated();
        $doesContentExists = isset($data['content']);
        $now = time();

        // File validation and receiving the mimes for uploading
        $contentMimes = [];
        if ($doesContentExists) {
            foreach ($data['content'] as $content) {
                $validateResult = $this->validateContentFile($content, $mime, $error);
                $contentMimes[] = $mime;
                if (!$validateResult) {
                    return $this->error(422, $error);
                }
            }
        }

        // Get the user assignment
        $userAssignment = UserAssignment::query()
            ->find($data['userAssignmentID']);
        if (!$userAssignment) {
            return $this->error($this->USER_ASSIGNMENT_NOT_FOUND, st('User assignment not found'));
        }

        // Get the assignment
        $cacheKey = Assignment::keyCache('_' . $userAssignment->assignmentID);
        $assignment = Cache::tags(Assignment::cacheTag())->remember(
            $cacheKey,
            now()->addMinutes(30),
            function () use ($userAssignment) {
                return Assignment::query()
                    ->where('ID', $userAssignment->assignmentID)
                    ->first();
            });
        if (!$assignment) {
            return $this->error($this->ASSIGNMENT_NOT_FOUND3, st('Assignment not found'));
        }

        // Handle deadline overing
        if ($userAssignment->created + ($assignment->deadline * 3600) < $now ) {
            return $this->error($this->DEADLINE_HAS_PASSED, st('Deadline has passed'));
        }

        // Handle retry count
        if ($userAssignment->retryCount >= $assignment->retryCount) {
            return $this->error($this->RETRY_LIMIT_REACHED, st('Retry limit reached'));
        }

        // Handle statuses which are allowed to send
        if (!in_array($userAssignment->status, [
            UserAssignmentStatuses::InProgress->value,
            UserAssignmentStatuses::Rejected->value,
        ])) {
            return $this->error($this->NOT_ALLOWED_TO_SEND, st('Not allowed to send'));
        }

        // Search for previous sent assignment
        $assignmentContent = AssignmentContent::query()
            ->where('userAssignmentID', $data['userAssignmentID'])
            ->get();
        if ($assignmentContent->isEmpty()) {
            $userAssignment->status = UserAssignmentStatuses::Pending->value;
        } else {
            $userAssignment->status = UserAssignmentStatuses::Resubmitted->value;

            // Remove previous content
            $previousContentSIDs = $assignmentContent
                ->pluck('contentSID')
                ->toArray();
            Storage::deleteBySIDs($previousContentSIDs);

            $assignmentContent->each(function ($model){
                $model->delete();
            });
        }

        // Storing the user assignment
        if ($doesContentExists) {
            foreach ($data['content'] as $index => $content) {
                $fileType = match (true) {
                    str_starts_with($contentMimes[$index], 'image/') => 'image',
                    str_starts_with($contentMimes[$index], 'video/') => 'video',
                };
                $storage = Storage::uploadFile(['type' => $fileType, 'file' => $content]);
                $SID = $storage->SID;

                $assignmentContent = AssignmentContent::query()
                    ->create([
                        'userAssignmentID' => $data['userAssignmentID'],
                        'contentSID' => $SID,
                        'created' => $now,
                    ]);

                $storage->used($assignmentContent, true);
            }
        }
        if (isset($data['text'])) {
            AssignmentContent::query()
                ->create([
                    'userAssignmentID' => $data['userAssignmentID'],
                    'text' => $data['text'],
                    'created' => $now,
                ]);
        }


        $userAssignment->retryCount += 1;
        $userAssignment->save();

        return $this->success();
    }

}

<?php

namespace App\Http\Controllers\Api;

use App\Enums\EpisodeStatuses;
use App\Enums\ExamStatuses;
use App\Enums\NotificationTypes;
use App\Enums\UserExamStatuses;
use App\Http\Requests\Api\Exam\AnswerRequest;
use App\Http\Requests\Api\Exam\GetRequest;
use App\Http\Requests\Api\Exam\JoinRequest;
use App\Http\Requests\Api\Exam\QuestionRequest;
use App\Http\Requests\Api\Exam\ResultRequest;
use App\Http\Resources\ExamResource;
use App\Http\Resources\QuestionResource;
use App\Http\Resources\UserExamResource;
use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\Notification;
use App\Models\Question;
use App\Models\SectionEpisode;
use App\Models\UserAnswers;
use App\Models\UserExam;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ExamController extends ApiController
{
    /**
     * @param JoinRequest $request
     * @return JsonResponse
     * @link https://docs.google.com/document/d/1lpyRPf0x_gHDMD9kWn7thvJ3AV3yb8yf05jOSwYIBWo/edit?tab=t.0
     */
    public function join(JoinRequest $request)
    {
        $data = $request->validated();
        $shouldStartNewExam = false;
        $shouldRejoin = false;

        // Get the episode
        $cacheKey = SectionEpisode::keyCache('_exam_' . $data['examID']);
        $episode = Cache::tags(SectionEpisode::cacheTag())->remember(
            $cacheKey,
            now()->addMinutes(30),
            function () use ($data) {
                return SectionEpisode::query()
                    ->where('examID', $data['examID'])
                    ->where('status', EpisodeStatuses::Published->value)
                    ->first();
            }
        );

        if (!$episode) {
            return $this->error(6, st('Episode not found'));
        }

        // Get the exam which user trys to join
        $cacheKey = Exam::keyCache('_' . $data['examID']);
        $exam = Cache::tags(Exam::cacheTag())->remember($cacheKey, now()->addMinutes(30), function () use ($data) {
            return Exam::query()
                ->where('ID', $data['examID'])
                ->withCount('questions as questionsCount') // Needed with this cache key
                ->first();
        });
        if (!$exam) {
            return $this->error(1, st('record not found'));
        }

        $joinPermission = $this->episodeExamPermission($episode);
        if (!$joinPermission['isAllowedToJoin']) {
            return $this->error($joinPermission['errorCode'], $joinPermission['errorMessage']);
        }

        // Check exam participate time
        $now = time();
        if ($exam->startDate > $now) {
            return $this->error(2, st('Exam not started error'));
        } elseif ($exam->endDate < $now) {
            return $this->error(3, st('Exam ended error'));
        }

        // Search for exam's result
        $userID = Auth::user()->ID;
        $examResults = UserExam::query()
            ->select(
                'ID',
                'examID',
                'userID',
                'examStatus',
                'retryCount',
            )
            ->where('examID', $data['examID'])
            ->where('userID', $userID)
            ->get();

        // If user has not participated in this exam yet, user should start a new exam.
        if ($examResults->isEmpty()) {
            $shouldStartNewExam = true;
            // If user has participated before, we should check something.
        } else {
            // First: check try attempts!
            $tries = 0;
            foreach ($examResults as $examResult) {
                $tries += $examResult->retryCount;
            }
            if ($tries >= $exam->retryAttempts) {
                return $this->error(4, st('Exam attempt error'));
            } else {
                // After passing the first step: Check that user were in an exam which is not ended.
                // If yes, we should handle rejoin process!
                $notEndedExamsStatuses = [UserExamStatuses::NotStarted->value, UserExamStatuses::InProgress->value];
                if ($inProgressExam = $examResults->whereIn('examStatus', $notEndedExamsStatuses)->first()) {
                    // Rejoin access
                    $shouldRejoin = true;
                } else {
                    // User was participated before but not reached the limit and also the exams has finished.
                    // So user should start a new exam.
                    $shouldStartNewExam = true;
                }
            }
        }

        // Find the exam's questionIDs
        $cacheKey = Question::keyCache('IDs_' . $data['examID']);
        $questionIDs = Cache::tags(Question::cacheTag())->remember(
            $cacheKey,
            now()->addMinutes(30),
            function () use ($data) {
                return ExamQuestion::query()
                    ->where('examID', $data['examID'])
                    ->pluck('questionID')
                    ->toArray();
            }
        );
        if (empty($questionIDs)) {
            return $this->error(5, st('no questions found'));
        }

        // Sort questionIDs by their order
        $cacheKey = Question::keyCache('_Sorted_' . $data['examID']);
        $questions = Cache::tags(Question::cacheTag())->remember(
            $cacheKey,
            now()->addMinutes(30),
            function () use ($questionIDs) {
                return Question::query()
                    ->whereIn('ID', $questionIDs)
                    ->with('options', 'options.storage', 'storage')
                    ->orderBy('sortOrder')
                    ->get();
            }
        );
        $questions = $questions
            ->pluck('ID')
            ->toArray();

        // Handle the response (one of this clause will execute by sure)
        if ($shouldStartNewExam) {
            UserExam::query()
                ->create([
                    'examID' => $exam->ID,
                    'userID' => $userID,
                    // Other parameters have default value.
                ]);
            $nextQuestionID = $questions[0];
            $nextQuestionNumber = 1;
        } elseif ($shouldRejoin) { // Just for readability
            // If user participated but have not started answering.
            if ($inProgressExam->examStatus == UserExamStatuses::NotStarted->value) {
                $nextQuestionID = $questions[0];
                $nextQuestionNumber = 1;

                $inProgressExam->retryCount++;
                // If user were in the middle of an exam and exited by any reason.
            } else {
                $lastSeenQuestion = UserAnswers::query()
                    ->where('userExamID', $inProgressExam->ID)
                    ->orderBy('created', 'desc') // Reversing the record to reach the last one.
                    ->first();

                $inProgressExam->retryCount++;

                $nextQuestionID = $lastSeenQuestion->questionID;
                $nextQuestionNumber = array_search($nextQuestionID, $questions) + 1;
            }
            $inProgressExam->save();
        }

        return $this->success([
            'nextQuestionID' => $nextQuestionID,
            'nextQuestionNumber' => $nextQuestionNumber,
            'totalQuestions' => count($questions),
        ]);
    }

    // Functions

    private function episodeExamPermission(SectionEpisode $episode): array
    {
        $isAllowedToJoin = true;
        $errorCode = null;
        $errorMessage = null;

        // Logic

        return [
            'isAllowedToJoin' => $isAllowedToJoin,
            'errorCode' => $errorCode,
            'errorMessage' => $errorMessage,
        ];
    }

    /**
     * @param GetRequest $request
     * @return JsonResponse
     * @link https://docs.google.com/document/d/1IKfAJEASz2QoIu8ru9xb478i1ytrmewI6vfGem3URTk/edit?tab=t.0
     */
    public function get(GetRequest $request)
    {
        $data = $request->validated();
        $status = 'todo';

        // Get the episode
        $cacheKey = SectionEpisode::keyCache('_exam_' . $data['examID']);
        $episode = Cache::tags(SectionEpisode::cacheTag())->remember(
            $cacheKey,
            now()->addMinutes(30),
            function () use ($data) {
                return SectionEpisode::query()
                    ->where('examID', $data['examID'])
                    ->where('status', EpisodeStatuses::Published->value)
                    ->first();
            }
        );

        if (!$episode) {
            return $this->error(2, st('Episode not found'));
        }

        $exam = Exam::query()
            ->where('ID', $data['examID'])
            ->withCount('questions as questionsCount')
            ->first();

        if (!$exam) {
            return $this->error(1, st('exam not found'));
        }

        // Search for exam's result
        $userID = Auth::user()->ID;
        $examResults = UserExam::query()
            ->select(
                'ID',
                'examID',
                'userID',
                'examStatus',
                'retryCount',
            )
            ->where('examID', $exam->ID)
            ->where('userID', $userID)
            ->get();

        $tries = 0;
        foreach ($examResults as $examResult) {
            $tries += $examResult->retryCount;
        }

        $joinPermission = $this->episodeExamPermission($episode);

        $userExamRecord = UserExam::query()
            ->where('userID', Auth::user()->ID)
            ->orderByDesc('ID')
            ->first();

        if ($userExamRecord) {
            $status = $userExamRecord->examStatus;
        }

        return $this->success([
            'exam' => ExamResource::make($exam),
            'joinPermission' => $joinPermission,
            'triesCount' => $tries,
            'status' => $status
        ]);
    }

    /**
     * @param QuestionRequest $request
     * @return JsonResponse
     * @link https://docs.google.com/document/d/1geFfSqaG5zZKwTzad-hbayZCF7wewDWCCK4dwKG5SH8/edit?tab=t.0
     */
    public function question(QuestionRequest $request)
    {
        $data = $request->validated();
        $hasMoreQuestions = true;
        $isExamEndTimeReached = false;

        // Find the examID
        $cacheKey = Question::keyCache($data['questionID']);
        $requestedQuestion = Cache::tags(Question::cacheTag())->remember(
            $cacheKey,
            now()->addMinutes(30),
            function () use ($data) {
                return Question::query()
                    ->where('ID', $data['questionID'])
                    ->with('examQuestion')
                    ->first();
            }
        );
        if (!$requestedQuestion) {
            return $this->error(1, st('record not found'));
        }
        $examID = $requestedQuestion->examQuestion->examID;

        // Get the episode
        $cacheKey = SectionEpisode::keyCache('_exam_' . $examID);
        $episode = Cache::tags(SectionEpisode::cacheTag())->remember(
            $cacheKey,
            now()->addMinutes(15),
            function () use ($examID) {
                return SectionEpisode::query()
                    ->where('examID', $examID)
                    ->where('status', EpisodeStatuses::Published->value)
                    ->first();
            }
        );

        if (!$episode) {
            return $this->error(6, st('Episode not found'));
        }

        $cacheKey = Exam::keyCache('_' . $examID);
        $exam = Cache::tags(Exam::cacheTag())->remember($cacheKey, now()->addMinutes(10), function () use ($examID) {
            return Exam::query()
                ->where('ID', $examID)
                ->withCount('questions as questionsCount') // Needed with this cache key
                ->first();
        });
        if (!$exam) {
            return $this->error(2, st('exam not found'));
        } elseif ($exam->endDate < time()) {
            $isExamEndTimeReached = true;
        }

        // Get all question IDs related to the current exam
        $cacheKey = Question::keyCache('IDs_' . $examID);
        $examQuestionIDs = Cache::tags(Question::cacheTag())->remember(
            $cacheKey,
            now()->addMinutes(10),
            function () use ($examID) {
                return ExamQuestion::query()
                    ->where('examID', $examID)
                    ->pluck('questionID')
                    ->toArray();
            }
        );

        // Sort questions by their order
        $cacheKey = Question::keyCache(Question::keyCache('_Sorted_' . $examID));
        $questions = Cache::tags(Question::cacheTag())->remember(
            $cacheKey,
            now()->addMinutes(10),
            function () use ($examQuestionIDs) {
                return Question::query()
                    ->whereIn('ID', $examQuestionIDs)
                    ->with('options', 'options.storage', 'storage')
                    ->orderBy('sortOrder')
                    ->get();
            }
        );
        $questions = $questions
            ->toArray(); // We array it after because we have and use this cache without array

        // Find the userExam record
        $userID = Auth::user()->ID;

        $cacheKey = UserExam::keyCache('_User_' . $userID);
        $userExam = Cache::tags(UserExam::cacheTag())->remember(
            $cacheKey,
            now()->addMinutes(30),
            function () use ($examID, $userID, $data) {
                return UserExam::query()
                    ->where('examID', $examID)
                    ->where('userID', $userID)
                    ->whereIn('examStatus', [UserExamStatuses::NotStarted->value, UserExamStatuses::InProgress->value])
                    ->first();
            }
        );
        if (!$userExam) {
            return $this->error(4, st('user exam not found'));
        }

        // Handle exam time limitation
        if ($isExamEndTimeReached) {
            $orderedQuestionIDs = array_column($questions, 'ID');
            $currentQuestionIndex = array_search($data['questionID'], $orderedQuestionIDs);
            $notAnsweredQuestionIDs = array_slice($orderedQuestionIDs, $currentQuestionIndex);

            $insertQueryData = [];
            foreach ($notAnsweredQuestionIDs as $notAnsweredQuestionID) {
                $insertQueryData[] = [
                    'userExamID' => $userExam->ID,
                    'questionID' => $notAnsweredQuestionID,
                    'created' => time(),
                ];
            }

            UserAnswers::query()
                ->insert($insertQueryData);

            return $this->error(3, st('exam ended while answering error'));
        }

        // Empty userAnswers record for this question
        $userAnswer = UserAnswers::query()
            ->create([
                'userExamID' => $userExam->ID,
                'questionID' => $data['questionID'],
                'created' => time(),
            ]);

        // Other parameters
        $orderedQuestionIDs = array_column($questions, 'ID');
        $currentQuestionIndex = array_search($data['questionID'], $orderedQuestionIDs);
        if ($currentQuestionIndex === false) { // Indexes start from 0 so if ==, 0 will consider false
            return $this->error(5, st('question not found'));
        }

        if (isset($questions[$currentQuestionIndex + 1])) {
            $nextQuestionID = $questions[$currentQuestionIndex + 1]['ID'];
            $nextQuestionNumber = $currentQuestionIndex + 2; // 1 for next question index and 1 for next question number
        } else {
            $hasMoreQuestions = false;
            $nextQuestionNumber = null;
            $nextQuestionID = null;
        }

        if ($userExam->examStatus == UserExamStatuses::NotStarted->value) {
            $userExam->examStatus = UserExamStatuses::InProgress->value;
            $userExam->save();
        }

        return $this->success([
            'question' => QuestionResource::make($requestedQuestion),
            'userAnswerID' => $userAnswer->ID, // We will receive this ID in AnswerSubmit API
            'nextQuestionID' => $nextQuestionID,
            'nextQuestionNumber' => $nextQuestionNumber,
            'totalQuestions' => count($questions),
            'hasMoreQuestions' => $hasMoreQuestions,
        ]);
    }

    /**
     * @param AnswerRequest $request
     * @return JsonResponse
     * @link https://docs.google.com/document/d/1X9E8Ec5nUfatbx0MB1zyxXdPgCrETwMrPtEsXybOZ7A/edit?tab=t.0
     */
    public function answer(AnswerRequest $request)
    {
        $data = $request->validated();

        // In userAnswer record, if optionID field be 0 its mean the timeLimit has been reached when user answered
        // But if its be null, its mean the user have not chosen any answer (like skipping)
        $selectedOptionID = isset($data['optionID']) ? 0 : null;

        // Find UserAnswer record (because of one time usage, we do not cache it)
        $userAnswerRecord = UserAnswers::query()
            ->find($data['userAnswerID']);

        // Getting answered question for calculate time limitation
        $answeredQuestion = Question::query()
            ->select(
                'ID',
                'timeLimit',
            )
            ->where('ID', $userAnswerRecord->questionID)
            ->with('options')
            ->first();
        if (!$answeredQuestion) {
            return $this->error(1, st('question not found'));
        }

        // Check time limit (if user have chose an option)
        if (
            isset($data['optionID']) && $userAnswerRecord->created + $answeredQuestion->timeLimit + 2 > time()
            // 2 more second for calculate process
        ) {
            $selectedAnswerOptionsIDs = array_column($answeredQuestion->options->toArray(), 'ID');
            if (in_array($data['optionID'], $selectedAnswerOptionsIDs)) {
                $selectedOptionID = $data['optionID'];
            } else {
                return $this->error(2, st('option not found'));
            }
        }

        $userAnswerRecord->optionID = $selectedOptionID;
        $userAnswerRecord->save();

        return $this->success();
    }

    /**
     * @param ResultRequest $request
     * @return JsonResponse
     * @link https://docs.google.com/document/d/1MnGDN3toxaFHqw6BJ1FTb1Gl4AXXYU6d9T-5LdWG0-I/edit?tab=t.0
     */
    public function result(ResultRequest $request)
    {
        $data = $request->validated();
        $user = Auth::user();
        $userExamRecord = null;

        if (isset($data['userAnswerID'])) {
            // Find UserAnswer record to get UserExamID and getting all this UserExam's answers
            $userAnswerRecord = UserAnswers::query()
                ->find($data['userAnswerID']);

            // Find UserExam to update the result
            $userExamRecord = UserExam::query()
                ->find($userAnswerRecord->userExamID);
        } elseif (isset($data['examID'])) {
            // Find UserExam to update the result
            $userExamRecord = UserExam::query()
                ->where('examID', $data['examID'])
                ->where('userID', $user->ID)
                ->whereIn('examStatus', [
                    UserExamStatuses::Passed->value,
                    UserExamStatuses::Failed->value,
                ])
                ->orderByDesc('updated')
                ->first();

            if (!$userExamRecord) {
                return $this->error(4, st('user exam not found'));
            }

            return $this->success([
                'userExam' => UserExamResource::make($userExamRecord),
                'isPassed' => ($userExamRecord->examStatus == UserExamStatuses::Passed->value),
            ]);
        }

        if (!$userExamRecord) {
            return $this->error(4, st('user exam not found'));
        }

        // Find the exam's questionIDs
        $cacheKey = Question::keyCache('IDs_' . $userExamRecord->examID);
        $questionIDs = Cache::tags(Question::cacheTag())->remember(
            $cacheKey,
            now()->addMinutes(5),
            function () use ($userExamRecord) {
                return ExamQuestion::query()
                    ->where('examID', $userExamRecord->examID)
                    ->pluck('questionID')
                    ->toArray();
            }
        );
        if (empty($questionIDs)) {
            return $this->error(1, st('no questions found'));
        }

        // All Exam's question with their correct answer
        $examQuestions = Question::query()
            ->select(
                'ID',
                'score',
            )
            ->whereIn('ID', $questionIDs)
            ->with('correctAnswer:ID,correct,questionID')
            ->get();
        if (empty($examQuestions)) {
            return $this->error(1, st('no questions found'));
        }

        // Array of user answers which its key-value is questionID-optionID
        $sortedSelectedOptionsByQuestions = UserAnswers::query()
            ->where('userExamID', $userExamRecord->ID)
            ->pluck('optionID', 'questionID')
            ->toArray();
        $filteredSelectedOptionsByQuestions = array_filter($sortedSelectedOptionsByQuestions); // Remove skipped answers

        // Array of true answers which its key-value is questionID-optionID
        $sortedCorrectOptionsByQuestions = $examQuestions
            ->whereIn('ID', array_keys($sortedSelectedOptionsByQuestions))
            ->pluck('correctAnswerID', 'ID')
            ->toArray(); // For comparing the answers

        // Answer checking
        $falseAnswers = array_diff($filteredSelectedOptionsByQuestions, $sortedCorrectOptionsByQuestions);
        $trueAnswers = array_diff($filteredSelectedOptionsByQuestions, $falseAnswers);
        $skippedAnswers = array_diff($sortedSelectedOptionsByQuestions, $filteredSelectedOptionsByQuestions);

        $answersCount = count($falseAnswers) + count($trueAnswers) + count($skippedAnswers);
        if ($answersCount !== count($examQuestions)) {
            return $this->error(2, st('Answer missing error'));
        }

        // Calculate received score
        $receivedScore = 0;
        foreach ($trueAnswers as $questionID => $optionID) {
            $receivedScore += $examQuestions
                ->where('ID', $questionID)
                ->first()
                ->score;
        }

        // Find the participated exam
        $cacheKey = Exam::keyCache('_' . $userExamRecord->examID);
        $exam = Cache::tags(Exam::cacheTag())->remember(
            $cacheKey,
            now()->addMinutes(5),
            function () use ($userExamRecord) {
                return Exam::query()
                    ->where('ID', $userExamRecord->examID)
                    ->withCount('questions as questionsCount') // Needed with this cache key
                    ->first();
            }
        );
        if (!$exam) {
            return $this->error(3, st('exam not found'));
        }

        $userExamStatus = UserExamStatuses::Failed->value;
        if ($receivedScore >= $exam->minScoreToPass) {
            $userExamStatus = UserExamStatuses::Passed->value;
        }

        $userExamRecord->update([
            'examStatus' => $userExamStatus,
            'score' => $receivedScore,
            'trueAnswers' => count($trueAnswers),
            'falseAnswers' => count($falseAnswers),
            'skippedAnswers' => count($skippedAnswers),
        ]);

        if ($userExamStatus == UserExamStatuses::Passed->value) {
            Notification::send(
                $user->ID,
                st('Notif - Exam - passed title'),
                st('Notif - Exam - passed content', ['name' => $user->name, 'title' => $exam->title]),
                NotificationTypes::Success->value,
            );
        } else {
            Notification::send(
                $user->ID,
                st('Notif - Exam - failed title'),
                st('Notif - Exam - failed content', ['name' => $user->name, 'title' => $exam->title]),
                NotificationTypes::Warning->value,
            );
        }

        return $this->success([
            'userExam' => UserExamResource::make($userExamRecord),
            'isPassed' => ($userExamStatus == UserExamStatuses::Passed->value),
        ]);
    }
}

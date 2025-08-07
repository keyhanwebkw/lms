<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ExamDatatable;
use App\DataTables\UserExamDatatable;
use App\Enums\EpisodeStatuses;
use App\Enums\UserExamStatuses;
use App\Http\Requests\Admin\Exam\CreateRequest;
use App\Http\Requests\Admin\Exam\UpdateRequest;
use App\Models\Answer;
use App\Models\Exam;
use App\Models\Question;
use App\Models\SectionEpisode;
use App\Models\User;
use App\Models\UserAnswers;
use App\Models\UserExam;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Keyhanweb\Subsystem\Http\Controllers\Web\Controller;

class ExamController extends Controller
{

    /**
     * @param CreateRequest $request
     * @return Application|RedirectResponse|Redirector|object
     */
    public function store(CreateRequest $request, SectionEpisode $sectionEpisode)
    {
        $data = $request->validated();

        $exam = Exam::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'startDate' => $data['startDate'],
            'endDate' => $data['endDate'],
            'duration' => $data['duration'],
            'minScoreToPass' => $data['minScoreToPass'],
            'retryAttempts' => $data['retryAttempts'],
            'managerID' => auth()->ID(),
        ]);

        $sectionEpisode->update([
            'examID' => $exam->ID,
        ]);

        return redirect()->route(
            'admin.course.section.episode.list',
            ['courseSectionID' => $sectionEpisode->courseSectionID]
        )
            ->with('success', st('Operation done successfully'));
    }

    /**
     * @return View
     */
    public function create(SectionEpisode $sectionEpisode)
    {
        return view('admin.exam.create', compact('sectionEpisode'));
    }

    /**
     * @param Exam $exam
     * @param UpdateRequest $request
     * @return RedirectResponse
     */
    public function update(Exam $exam, UpdateRequest $request)
    {
        $data = $request->validated();

        $exam->update([
            'title' => $data['title'],
            'description' => $data['description'],
            'startDate' => $data['startDate'],
            'endDate' => $data['endDate'],
            'duration' => $data['duration'],
            'minScoreToPass' => $data['minScoreToPass'],
            'retryAttempts' => $data['retryAttempts'],
        ]);

        return redirect($data['returnUrl'])->with('success', st('Operation done successfully'));
    }

    /**
     * @return View
     */
    public function edit(Exam $exam)
    {
        return view('admin.exam.edit', compact('exam'));
    }

    /**
     * @param $relationName
     * @param $relationID
     * @return View
     */
    public function list(ExamDatatable $datatable)
    {
        return $datatable->render('admin.exam.list');
    }

    /**
     * @param UserExamDatatable $datatable
     * @return mixed
     */
    public function attendees(UserExamDatatable $datatable)
    {
        $userIDs = UserExam::query()
            ->where('examID', request('examID'))
            ->pluck('userID');

        $users = User::query()
            ->whereIn('ID', $userIDs)
            ->get()
            ->pluck('fullName', 'ID'); // virtual fields are available after query

        $examStatuses = UserExamStatuses::valuesTranslate();

        $examTitle = Exam::query()
            ->select(
                'ID',
                'title'
            )
            ->where('ID', request('examID'))
            ->first()
            ?->title;
        if (!$examTitle) {
            return abort(404);
        }

        return $datatable->render('admin.exam.attendees', compact('users', 'examStatuses', 'examTitle'));
    }

    /**
     * @param UserExam $userExam
     * @return View
     */
    public function answerSheet(UserExam $userExam)
    {
        // Array which questionID-optionID as key-value
        $userAnswers = UserAnswers::query()
            ->where('userExamID', $userExam->ID)
            ->pluck('optionID', 'questionID')
            ->toArray();

        // User's answer
        $answers = Answer::query()
            ->select(
                'ID',
                'answer',
                'correct'
            )
            ->whereIn('ID', $userAnswers)
            ->get();

        // Exam's question
        $questions = Question::query()
            ->select(
                'ID',
                'contentSID',
                'question',
                'sortOrder'
            )
            ->whereIn('ID', array_keys($userAnswers))
            ->orderBy('sortOrder')
            ->get();

        $questionData = [];
        foreach ($questions as $question) {
            $optionID = $userAnswers[$question->ID];
            $option = $answers->find($optionID);

            $questionData[] = [
                'question' => $question->question,
                'contentSID' => $question->contentSID,
                'answer' => $option?->answer,
                'isCorrect' => $option?->correct,
            ];
        }

        // Other Data
        $exam = Exam::query()
            ->select(
                'ID',
                'minScoreToPass'
            )
            ->find($userExam->examID);
        $userName = User::query()
            ->find($userExam->userID)
            ->fullName;

        return view('admin.exam.answerSheet', compact('questionData', 'userName', 'userExam', 'exam'));
    }
}

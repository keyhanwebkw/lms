<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\QuestionDatatable;
use App\Enums\questionDifficultyLevel;
use App\Http\Requests\Admin\Question\CreateRequest;
use App\Http\Requests\Admin\Question\UpdateRequest;
use App\Models\Answer;
use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\Question;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Keyhanweb\Subsystem\Http\Controllers\Web\Controller;
use Keyhanweb\Subsystem\Models\Storage;

class QuestionController extends Controller
{
    /**
     * @param Exam $exam
     * @param QuestionDatatable $datatable
     * @return mixed
     */
    public function list(QuestionDatatable $datatable)
    {
        $difficultyLevel = questionDifficultyLevel::valuesTranslate();
        $exam = Exam::findOrFail(request('examID'));

        return $datatable->render('admin.question.list', compact('difficultyLevel', 'exam'));
    }

    public function store(CreateRequest $request)
    {
        $data = $request->validated();
        $contentExist = isset($data['content']);

        if ($contentExist) {
            $storage = Storage::uploadFile(['file' => $data['content'], 'type' => 'image']);
            $data['contentSID'] = $storage->SID;
        }

        $question = Question::query()
            ->create([
                'question' => $data['question'],
                'questionDifficultyLevel' => $data['questionDifficultyLevel'],
                'timeLimit' => $data['timeLimit'],
                'score' => $data['score'],
                'contentSID' => $data['contentSID'] ?? null,
                'sortOrder' => $data['sortOrder'],
                'managerID' => Auth::ID(),
            ]);

        if ($contentExist) {
            $storage->used($question, true);
        }

        foreach ($data['answers'] as $key => $answer) {
            Answer::query()
                ->create([
                    'answer' => $answer,
                    'questionID' => $question->ID,
                    'correct' => $key == $data['correct'],
                    'managerID' => Auth::ID(),
                ]);
        }

        ExamQuestion::create([
            'questionID' => $question->ID,
            'examID' => $data['examID'],
        ]);

        return back()->with('success', st('Operation done successfully'));
    }

    public function create(Exam $exam)
    {
        $difficultyLevel = questionDifficultyLevel::valuesTranslate();
        return view('admin.question.create', compact('difficultyLevel', 'exam'));
    }

    /**
     * @param Question $question
     * @return View
     */
    public function edit(Question $question)
    {
        $question->load('examQuestion');
        $exam = Exam::query()->findOrFail($question->examQuestion->examID);

        $difficultyLevel = questionDifficultyLevel::valuesTranslate();
        $answers = Answer::query()
            ->where('questionID', $question->ID)
            ->get();

        $correct = $answers->firstWhere('correct', true)->ID;
        $answers = $answers->pluck('answer', 'ID');

        $contentPath = null;
        if ($question->contentSID) {
            $content = Storage::findBySID($question->contentSID);
            $contentPath = $content->SID . '.' . $content->extension;
        }

        return view(
            'admin.question.edit',
            compact('question', 'difficultyLevel', 'answers', 'correct', 'exam', 'contentPath')
        );
    }

    /**
     * @param UpdateRequest $request
     * @param Question $question
     * @return RedirectResponse
     */
    public function update(UpdateRequest $request, Question $question)
    {
        $data = $request->validated();
        $currentAnswers = Answer::query()
            ->where('questionID', $question->ID)
            ->get()
            ->keyBy('ID');

        $currentCorrectAnswerID = $currentAnswers->firstWhere('correct', true)->ID;
        $currentAnswersValue = $currentAnswers->pluck('answer', 'ID')->toArray();

        $currentAnswersString = json_encode($data['answers']);
        $newAnswersString = json_encode($currentAnswersValue);

        // Handle answers change
        if ($currentAnswersString != $newAnswersString) {
            Answer::query()
                ->whereIn('ID', array_keys($currentAnswersValue))
                ->delete();

            $insertQueryData = [];
            $now = time();
            foreach ($data['answers'] as $key => $answer) {
                $insertQueryData[] = [
                    'answer' => $answer,
                    'questionID' => $question->ID,
                    'correct' => $key == $data['correct'],
                    'managerID' => Auth::ID(),
                    'created' => $now,
                    'updated' => $now,
                ];
            }
            Answer::insert($insertQueryData);
        } elseif ($currentCorrectAnswerID != $data['correct']) {
            // New correct answer
            $newCorrectAnswer = $currentAnswers[$data['correct']];
            $newCorrectAnswer->correct = true;
            $newCorrectAnswer->save();
            // Previous correct answer
            $previousCorrectAnswer = $currentAnswers[$currentCorrectAnswerID];
            $previousCorrectAnswer->correct = false;
            $previousCorrectAnswer->save();
        }

        $contentExist = isset($data['content']);
        if ($contentExist) {
            Storage::deleteBySID($question->contentSID);

            $storage = Storage::uploadFile(['file' => $data['content'], 'type' => 'image']);
            $data['contentSID'] = $storage->SID;
        }

        $question->fill([
            'questionDifficultyLevel' => $data['questionDifficultyLevel'],
            'timeLimit' => $data['timeLimit'],
            'question' => $data['question'],
            'score' => $data['score'],
            'sortOrder' => $data['sortOrder'],
            'contentSID' => $contentExist ? $data['contentSID'] : $question->contentSID,
        ]);
        $question->save();

        if ($contentExist) {
            $storage->used($question, true);
        }

        return redirect()->route('admin.question.list', ['examID' => $question->examQuestion->examID])->with(
            'success',
            st('Operation done successfully')
        );
    }
}

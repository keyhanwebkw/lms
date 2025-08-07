<?php

namespace App\DataTables;

use App\Enums\questionDifficultyLevel;
use App\Models\ExamQuestion;
use App\Models\Question;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Keyhanweb\Subsystem\DataTables\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class QuestionDatatable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->filter(function ($query) {
                if (request()->filled('question')) {
                    $query->where('question', 'like', "%" . request('question') . "%");
                }
                if (request()->filled('difficultyLevel')) {
                    $query->where('questionDifficultyLevel', request('difficultyLevel'));
                }
                if (request()->filled('score')) {
                    $query->where('score', request('score'));
                }
                return $query;
            })
            ->editColumn('questionDifficultyLevel', function ($model) {
                return questionDifficultyLevel::valuesTranslate()[$model->questionDifficultyLevel] ?? '-';
            })
            ->addColumn('edit', function ($model) {
                return $this->actionEdit(route('admin.question.edit', $model->ID));
            })
            ->rawColumns(['edit'])
            ->setTotalRecords($query->count())
            ->addIndexColumn()
            ->orderColumns(['ID'], ':column $1')
            ->setRowId('ID');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Question $model): QueryBuilder
    {
        $questionIDs = ExamQuestion::query()
            ->where('examID', request('examID'))
            ->pluck('questionID')
            ->toArray();

        return $model->newQuery()
            ->whereIn('ID', $questionIDs)
            ->select(
                'ID',
                'question',
                'questionDifficultyLevel',
                'score',
                'timeLimit',
            );
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('#')->orderable(false),
            Column::make('question')->title(st('Question'))->orderable(false),
            Column::make('questionDifficultyLevel')->title(st('Difficulty Level')),
            Column::make('score')->title(st('Score')),
            Column::make('timeLimit')->title(st('Time Limit (seconds)')),
            Column::make('edit')->title(st('Edit')),
        ];
    }
}

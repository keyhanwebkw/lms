<?php

namespace App\DataTables;

use App\Enums\UserExamStatuses;
use App\Models\UserExam;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Keyhanweb\Subsystem\DataTables\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class UserExamDatatable extends DataTable
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
                if (request('examID')) {
                    $query->where('examID', request('examID'));
                }
                if (request('userID')) {
                    $query->where('userID', request('userID'));
                }
                if (request('examStatus')) {
                    $query->where('examStatus', request('examStatus'));
                }
                return $query;
            })
            ->addColumn('user', function ($model) {
                return $model->user->fullName;
            })
            ->editColumn('examStatus', function ($model) {
                return $this->examStatusAction($model);
            })
            ->addColumn('answerSheet', function ($model) {
                return $this->answerSheetAction($model);
            })
            ->rawColumns(['answerSheet', 'examStatus'])
            ->setTotalRecords($query->count())
            ->addIndexColumn()
            ->orderColumn('ID', ':column $1')
            ->setRowId('ID');
    }

    public function examStatusAction($model)
    {
        $textClass = match ($model->examStatus) {
            UserExamStatuses::Passed->value => 'text-success',
            UserExamStatuses::Failed->value => 'text-danger',
            default => 'text-warning',
        };

        return '<span class="' . $textClass . '">' . UserExamStatuses::getTranslateFormat(
                $model->examStatus
            ) . '</span>';
    }

    public function answerSheetAction($model)
    {
        $finishedExamStatuses = [
            UserExamStatuses::Passed->value,
            UserExamStatuses::Failed->value,
        ];

        $route = null;
        if (in_array($model->examStatus, $finishedExamStatuses)) {
            $route = route('admin.exam.answerSheet', $model->ID, ['examID' => $model->examID]);
        }
        return $this->actionShow($route);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(UserExam $model): QueryBuilder
    {
        return $model->newQuery()
            ->select(
                'ID',
                'userID',
                'examStatus',
                'trueAnswers',
                'falseAnswers',
                'skippedAnswers',
            )
            ->with('user')
            ->orderBy('created', 'desc');
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('#')->orderable(false),
            Column::make('user')->title(st('User name'))->orderable(false),
            Column::make('trueAnswers')->title(st('True answers'))->orderable(false),
            Column::make('falseAnswers')->title(st('False answers'))->orderable(false),
            Column::make('skippedAnswers')->title(st('Skipped answers'))->orderable(false),
            Column::make('examStatus')->title(st('Exam status'))->orderable(false),
            Column::make('answerSheet')->title(st('Answer sheet'))->orderable(false),
        ];
    }
}

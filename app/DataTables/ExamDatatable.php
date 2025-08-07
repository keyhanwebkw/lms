<?php

namespace App\DataTables;

use App\Models\Exam;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Keyhanweb\Subsystem\DataTables\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class ExamDatatable extends DataTable
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
                if (request('ID')) {
                    $query->where('ID', request('ID'));
                }
                if (request('title')) {
                    $query->where('title', 'like', '%' .  request('title') . '%');
                }
                if (request()->filled('startDate')) {
                    $query->where('startDate', '>=', $this->parseTimeStamp(request('startDate')));
                }
                if (request()->filled('endDate')) {
                    $query->where('endDate', '<=', $this->parseTimeStamp(request('endDate')));
                }
                return $query;
            })
            ->editColumn('startDate', function ($model) {
                return $this->parseDate($model->startDate);
            })
            ->editColumn('endDate', function ($model) {
                return $this->parseDate($model->endDate);
            })
            ->addColumn('questions', function ($model) {
                return $this->questionAction($model);
            })
            ->addColumn('attendeesList', function ($model) {
                return $this->attendeesListAction($model);
            })
            ->addColumn('edit', function ($model) {
                return $this->actionEdit(route('admin.exam.edit', $model->ID));
            })
            ->rawColumns(['questions', 'attendeesList', 'edit'])
            ->setTotalRecords($query->count())
            ->addIndexColumn()
            ->orderColumn('ID', ':column $1')
            ->setRowId('ID');
    }

    public function questionAction($model)
    {
        return '<div><a class="btn btn-indigo" href="' . route(
                'admin.question.list',
                ['examID' => $model->ID]
            ) . '" ><i class="far fa-lg fa-eye"></i>&nbsp</a></div>';
    }

    public function attendeesListAction($model)
    {
        return '<div><a class="btn btn-teal" href="' . route(
                'admin.exam.attendees',
                ['examID' => $model->ID]
            ) . '" ><i class="far fa-lg fa-eye"></i>&nbsp</a></div>';
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Exam $model): QueryBuilder
    {
        return $model->newQuery()
            ->select(
                'ID',
                'title',
                'startDate',
                'endDate',
                'score',
                'minScoreToPass',
            );
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('#')->orderable(false),
            Column::make('title')->title(st('Title'))->orderable(false),
            Column::make('startDate')->title(st('startDate'))->orderable(false),
            Column::make('endDate')->title(st('endDate'))->orderable(false),
            Column::make('score')->title(st('Score')),
            Column::make('minScoreToPass')->title(st('MinScoreToPass')),
            Column::make('questions')->title(st('Questions'))->orderable(false),
            Column::make('attendeesList')->title(st('Attendees list'))->orderable(false),
            Column::make('edit')->title(st('Edit'))->orderable(false),
        ];
    }
}

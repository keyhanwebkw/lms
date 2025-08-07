<?php

namespace App\DataTables;

use App\Enums\UserAssignmentStatuses;
use App\Models\UserAssignment;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Keyhanweb\Subsystem\DataTables\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class UserAssignmentDatatable extends DataTable
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
                if (request('assignmentID')) {
                    $query->where('assignmentID', request('assignmentID'));
                }
                if (request('userID')) {
                    $query->where('userID', request('userID'));
                }
                if (request('status')) {
                    $query->where('status', request('status'));
                }
                return $query;
            })
            ->editColumn('status', function ($model) {
                return $this->statusAction($model);
            })
            ->editColumn('userName', function ($model) {
                return $model->user->fullname;
            })
            ->editColumn('sendDate', function ($model) {
                return $this->parseDate($model
                    ->content
                    ->sortByDesc('created')
                    ->first()
                    ->created
                );
            })
            ->addColumn('show', function ($model) {
                return $this->actionShow(route('admin.assignment.check.show',$model->ID));
            })
            ->rawColumns(['status','show'])
            ->setTotalRecords($query->count())
            ->addIndexColumn()
            ->orderColumn('ID', ':column $1')
            ->setRowId('ID');
    }

    public function statusAction($model)
    {
        $textClass = match ($model->status) {
            UserAssignmentStatuses::Accepted->value => 'text-success',
            UserAssignmentStatuses::Rejected->value => 'text-danger',
            default => 'text-warning',
        };

        return '<span class="' . $textClass . '">' . UserAssignmentStatuses::getTranslateFormat(
                $model->status
            ) . '</span>';
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(UserAssignment $model): QueryBuilder
    {
        return $model->newQuery()
            ->select(
                'ID',
                'userID',
                'status',
                'created',
                'retryCount',
            )
            ->with('user','content')
            ->has('content');
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('#')->orderable(false),
            Column::make('userName')->title(st('User name'))->orderable(false),
            Column::make('status')->title(st('status'))->orderable(false),
            Column::make('retryCount')->title(st('Retry count'))->orderable(false),
            Column::make('sendDate')->title(st('Send date'))->orderable(false),
            Column::make('show')->title(st('Show'))->orderable(false),
        ];
    }
}

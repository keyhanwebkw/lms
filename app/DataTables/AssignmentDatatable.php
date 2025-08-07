<?php

namespace App\DataTables;

use App\Models\Assignment;
use App\Models\SectionEpisode;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Keyhanweb\Subsystem\DataTables\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class AssignmentDatatable extends DataTable
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
                    $query->where('title', 'like', '%' . request('title') . '%');
                }
                return $query;
            })
            ->addColumn('edit', function ($model) {
                return $this->actionEdit(route('admin.assignment.edit', $model->ID));
            })
            ->addColumn('sentAssignment', function ($model) {
                return $this->sentAssignmentAction($model);
            })
            ->rawColumns(['edit', 'archive', 'sentAssignment'])
            ->setTotalRecords($query->count())
            ->addIndexColumn()
            ->orderColumn('ID', ':column $1')
            ->setRowId('ID');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Assignment $model): QueryBuilder
    {
        return $model->newQuery()
            ->select(
                'ID',
                'title',
                'deadline',
            );
    }

    public function sentAssignmentAction($model)
    {
        return '<div>
            <a class="btn mx-auto btn-sm btn-indigo" href="' . route(
                'admin.assignment.check.list',
                ['assignmentID' => $model->ID]
            ) . '">
                <i class="far fa-lg fa-book"></i>&nbsp</a>
        </div>';
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('#')->orderable(false),
            Column::make('title')->title(st('Title'))->orderable(false),
            Column::make('deadline')->title(st('Deadline'))->orderable(false),
            Column::make('sentAssignment')->title(st('Sent assignments'))->orderable(false),
            Column::make('edit')->title(st('Edit'))->orderable(false),
        ];
    }
}

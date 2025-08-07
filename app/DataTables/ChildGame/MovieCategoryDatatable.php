<?php

namespace App\DataTables\ChildGame;

use App\Models\MovieCategory;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Keyhanweb\Subsystem\DataTables\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class MovieCategoryDatatable extends DataTable
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
                if (request('title')) {
                    $query->where('title', 'like', '%' . request('title') . '%');
                }
                if (request('sortOrder')) {
                    $query->where('sortOrder', request('sortOrder'));
                }
                match (request('status')) {
                    'archived' => $query->withoutGlobalScope('archive')->whereNotNull('archived'),
                    'all' => $query->withoutGlobalScope('archive'),
                    default => $query,
                };
                return $query;
            })
            ->editColumn('photo', function ($model) {
                return $this->getImage($model->photoSID);
            })
            ->addColumn('edit', function ($model) {
                return $this->actionEdit(
                    route('admin.cg.movieCategory.edit', $model->ID),
                    $model->archived ? 'style="cursor: not-allowed; opacity: 0.5;" onclick="return false;" title=" ' . st(
                            'First, unarchive it'
                        ) . '"' : ''
                );
            })
            ->addColumn('archive', function ($model) {
                return $this->actionArchive(
                    $model->archived,
                    route('admin.cg.movieCategory.archive', $model->ID),
                    route('admin.cg.movieCategory.unarchive', $model->ID)
                );
            })
            ->rawColumns(['photo', 'edit', 'archive'])
            ->setTotalRecords($query->count())
            ->addIndexColumn()
            ->orderColumn('ID', ':column $1')
            ->setRowId('ID');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(MovieCategory $model): QueryBuilder
    {
        return $model->newQuery()
            ->orderBy('sortOrder');
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('#')->orderable(false),
            Column::make('photo')->title(st('Photo'))->orderable(false),
            Column::make('title')->title(st('Title'))->orderable(false),
            Column::make('sortOrder')->title(st('SortOrder')),
            Column::make('edit')->title(st('Edit'))->orderable(false),
            Column::make('archive')->title(st('archive'))->orderable(false),
        ];
    }
}

<?php

namespace App\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Keyhanweb\Subsystem\DataTables\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use App\Models\EpisodeContent;

class EpisodeContentDatatable extends DataTable
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
                if (request('title')){
                    $query->where('title', 'like', '%'. request('title') .'%');
                }
                if (request('ID')){
                    $query->where('ID', request('ID'));
                }
                return $query;
            })
            ->addColumn('edit', function ($model) {
                return $this->actionEdit(route('admin.course.section.episode.content.edit', $model->ID));
            })
            ->rawColumns(['edit'])
            ->setTotalRecords($query->count())
            ->addIndexColumn()
            ->orderColumn('ID', ':column $1')
            ->setRowId('ID');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(EpisodeContent $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('#')->orderable(false),
            Column::make('title')->title(st('Title'))->orderable(false),
            Column::make('duration')->title(st('duration'))->orderable(false),
            Column::make('description')->title(st('description'))->orderable(false),
            Column::make('edit')->title(st('Edit'))->orderable(false),
        ];
    }
}

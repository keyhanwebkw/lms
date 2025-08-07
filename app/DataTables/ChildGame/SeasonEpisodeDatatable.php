<?php

namespace App\DataTables\ChildGame;

use App\Models\SeasonEpisode;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Keyhanweb\Subsystem\DataTables\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class SeasonEpisodeDatatable extends DataTable
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
                if (request('movieID')){
                    $query->where('movieID', request('movieID'));
                }
                if (request('movieSeasonID')){
                    $query->where('seasonID', request('movieSeasonID'));
                }
                return $query;
            })
            ->addColumn('specificVideo', function ($model) {
                return match (true){
                    isset($model->videoSID) => st('Has'),
                    default => st('Has not'),
                };
            })
            ->editColumn('videoUrl', function ($model) {
                return match (true){
                    isset($model->videoUrl) => st('Has'),
                    default => st('Has not'),
                };
            })
            ->editColumn('title', function ($model) {
                return $this->optional($model->title);
            })
            ->editColumn('sortOrder', function ($model) {
                return $this->optional($model->sortOrder);
            })
            ->addColumn('edit', function ($model) {
                return $this->actionEdit(route('admin.cg.seasonEpisode.edit', $model->ID));
            })
            ->addColumn('delete', function ($model) {
                return $this->actionDelete(route('admin.cg.seasonEpisode.delete', $model->ID));
            })
            ->rawColumns(['edit', 'delete'])
            ->setTotalRecords($query->count())
            ->addIndexColumn()
            ->orderColumn('ID', ':column $1')
            ->setRowId('ID');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(SeasonEpisode $model): QueryBuilder
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
            Column::make('title')->title(st('Title'))->orderable(false),
            Column::make('specificVideo')->title(st('Specific video'))->orderable(false),
            Column::make('videoUrl')->title(st('videoUrl'))->orderable(false),
            Column::make('sortOrder')->title(st('SortOrder')),
            Column::make('edit')->title(st('Edit')),
            Column::make('delete')->title(st('Delete')),
        ];
    }
}

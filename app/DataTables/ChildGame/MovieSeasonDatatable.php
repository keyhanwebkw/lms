<?php

namespace App\DataTables\ChildGame;

use App\Models\MovieSeason;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Keyhanweb\Subsystem\DataTables\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class MovieSeasonDatatable extends DataTable
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
                if (request('movieID')) {
                    $query->where('movieID', request('movieID'));
                }
                if (request('name')) {
                    $query->where('name', 'like', '%' . request('name') . '%');
                }
                match (request('status')) {
                    'archived' => $query->withoutGlobalScope('archive')->whereNotNull('archived'),
                    'all' => $query->withoutGlobalScope('archive'),
                    default => $query,
                };
                return $query;
            })
            ->editColumn('relatedTo', function ($model) {
                return $model->movie->name;
            })
            ->addColumn('edit', function ($model) {
                return $this->actionEdit(
                    route('admin.cg.movieSeason.edit', $model->ID),
                    $model->archived ? 'style="cursor: not-allowed; opacity: 0.5;" onclick="return false;" title=" ' . st(
                            'First, unarchive it'
                        ) . '"' : ''
                );
            })
            ->addColumn('archive', function ($model) {
                return $this->actionArchive(
                    $model->archived,
                    route('admin.cg.movieSeason.archive', $model->ID),
                    route('admin.cg.movieSeason.unarchive', $model->ID)
                );
            })
            ->addColumn('episodes', function ($model) {
                return $this->episodesAction($model);
            })
            ->rawColumns(['edit', 'archive', 'episodes'])
            ->setTotalRecords($query->count())
            ->addIndexColumn()
            ->orderColumn('ID', ':column $1')
            ->setRowId('ID');
    }

    public function episodesAction($model)
    {
        $htmlAttr = $model->archived ? 'style="cursor: not-allowed; opacity: 0.5;" onclick="return false;" title=" ' . st(
                'First, unarchive it'
            ) . '"' : '';

        return '<div>
            <a class="btn btn-sm btn-flat-info" ' . $htmlAttr . ' href="' . route(
            'admin.cg.seasonEpisode.list',
            ['movieSeasonID' => $model->ID]
        ) . '" >' . st('Show') . ' - ' . $model->episodeCount .
        '</a>';
    }
    /**
     * Get the query source of dataTable.
     */
    public function query(MovieSeason $model): QueryBuilder
    {
        return $model->newQuery()
            ->with('movie')
            ->withCount('episodes as episodeCount')
            ->orderBy('sortOrder');
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('#')->orderable(false),
            Column::make('relatedTo')->title(st('Related to'))->orderable(false),
            Column::make('name')->title(st('name'))->orderable(false),
            Column::make('sortOrder')->title(st('SortOrder')),
            Column::make('episodes')->title(st('Episodes')),
            Column::make('edit')->title(st('Edit')),
            Column::make('archive')->title(st('Archive')),
        ];
    }
}

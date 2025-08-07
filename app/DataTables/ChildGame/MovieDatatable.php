<?php

namespace App\DataTables\ChildGame;

use App\Enums\MovieTypes;
use App\Models\Movie;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Keyhanweb\Subsystem\DataTables\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class MovieDatatable extends DataTable
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
                if (request('name')) {
                    $query->where('name', 'like', '%' . request('name') . '%');
                }
                if (request('type')) {
                    $query->where('type', request('type'));
                }
                match (request('status')) {
                    'archived' => $query->withoutGlobalScope('archive')->whereNotNull('archived'),
                    'all' => $query->withoutGlobalScope('archive'),
                    default => $query,
                };
                return $query;
            })
            ->editColumn('poster', function ($model) {
                return $this->getImage($model->posterSID);
            })
            ->editColumn('type', function ($model) {
                return MovieTypes::getTranslateFormat($model->type);
            })
            ->addColumn('edit', function ($model) {
                return $this->actionEdit(
                    route('admin.cg.movie.edit', $model->ID),
                    $model->archived ? 'style="cursor: not-allowed; opacity: 0.5;" onclick="return false;" title=" ' . st(
                            'First, unarchive it'
                        ) . '"' : ''
                );
            })
            ->addColumn('archive', function ($model) {
                return $this->actionArchive(
                    $model->archived,
                    route('admin.cg.movie.archive', $model->ID),
                    route('admin.cg.movie.unarchive', $model->ID)
                );
            })
            ->addColumn('content', function ($model) {
                return $this->contentAction($model);
            })
            ->rawColumns(['poster', 'edit', 'archive', 'content'])
            ->setTotalRecords($query->count())
            ->addIndexColumn()
            ->orderColumn('ID', ':column $1')
            ->setRowId('ID');
    }

    public function contentAction($model)
    {
        $htmlAttr = $model->archived ? 'style="cursor: not-allowed; opacity: 0.5;" onclick="return false;" title=" ' . st(
                'First, unarchive it'
            ) . '"' : '';
        $seriesResponse = '<div>
            <a class="btn btn-sm btn-flat-teal" ' . $htmlAttr . ' href="' . route(
                'admin.cg.movieSeason.list',
                ['movieID' => $model->ID]
            ) . '" >' . st('Seasons') . ' - ' . $model->seasonsCount .
            '</a>';
        $filmResponse = '<div>
            <a class="btn btn-sm btn-flat-indigo" ' . $htmlAttr . ' href="' . route(
                'admin.cg.seasonEpisode.list',
                ['movieID' => $model->ID]
            ) . '" >' . st('Show') .
            '</a>';

        return match ($model->type) {
            MovieTypes::Series->value => $seriesResponse,
            MovieTypes::Film->value => $filmResponse,
            default => '-',
        };
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Movie $model): QueryBuilder
    {
        return $model->newQuery()
            ->withCount('seasons as seasonsCount')
            ->orderBy('ID', 'desc');
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('#')->orderable(false),
            Column::make('poster')->title(st('Poster'))->orderable(false),
            Column::make('name')->title(st('name'))->orderable(false),
            Column::make('type')->title(st('type'))->orderable(false),
            Column::make('content')->title(st('Content'))->orderable(false),
            Column::make('edit')->title(st('Edit'))->orderable(false),
            Column::make('archive')->title(st('archive'))->orderable(false),
        ];
    }
}

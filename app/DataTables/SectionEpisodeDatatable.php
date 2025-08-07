<?php

namespace App\DataTables;

use App\Enums\EpisodeStatuses;
use App\Models\SectionEpisode;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Keyhanweb\Subsystem\DataTables\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class SectionEpisodeDatatable extends DataTable
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
                if (request('courseSectionID')) {
                    $query->where('courseSectionID', request('courseSectionID'));
                }
                return $query;
            })
            ->addColumn('sectionName', function ($model) {
                return $model->courseSection->title;
            })
            ->addColumn('episodeType', function ($model) {
                return $this->episodeTypeAction($model);
            })
            ->addColumn('list', function ($model) {
                return $this->listAction($model);
            })
            ->addColumn('edit', function ($model) {
                return $this->actionEdit(route('admin.course.section.episode.edit', $model->ID));
            })
            ->editColumn('status', function ($model){
                return EpisodeStatuses::getTranslateFormat($model->status);
            })
            ->rawColumns(['list', 'edit'])
            ->setTotalRecords($query->count())
            ->addIndexColumn()
            ->orderColumn('ID', ':column $1')
            ->setRowId('ID');
    }

    public function episodeTypeAction($model)
    {
        $type = match (true) {
            isset($model->episodeContentID) => st('content'),
            isset($model->assignmentID) => st('Assignment'),
            isset($model->examID) => st('Exam'),
        };

        return $type;
    }

    public function listAction($model)
    {
        $parameters = match (true) {
            isset($model->episodeContentID) => [
                route(
                    'admin.course.section.episode.content.list',
                    ['ID' => $model->episodeContentID, 'courseSectionID' => $model->courseSectionID]
                ),
                'btn-flat-info'
            ],
            isset($model->assignmentID) => [
                route(
                    'admin.assignment.list',
                    ['ID' => $model->assignmentID, 'courseSectionID' => $model->courseSectionID]
                ),
                'btn-flat-warning'
            ],
            isset($model->examID) => [
                route(
                    'admin.exam.list',
                    ['ID' => $model->examID, 'courseSectionID' => $model->courseSectionID]
                ),
                'btn-flat-indigo'
            ],
        };

        return '<div><a class="btn ' . $parameters[1] . '" href="' . $parameters[0] . '" ><i class="far fa-lg fa-eye"></i>&nbsp</a></div>';
    }


    /**
     * Get the query source of dataTable.
     */
    public function query(SectionEpisode $model): QueryBuilder
    {
        return $model->newQuery()
            ->select(
                'ID',
                'sortOrder',
                'courseSectionID',
                'status',
                'episodeContentID',
                'assignmentID',
                'examID',
            )
            ->with('courseSection')
            ->orderBy('sortOrder');
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        $columns = [
            Column::make('DT_RowIndex')->title('#')->orderable(false),
            Column::make('sectionName')->title(st('Section name'))->orderable(false),
            Column::make('episodeType')->title(st('Episode type'))->orderable(false),
            Column::make('sortOrder')->title(st('Sort Order')),
            Column::make('status')->title(st('Status')),
            Column::make('list')->title(st('Show')),
            Column::make('edit')->title(st('Edit')),
        ];

        if (request('courseSectionID')) {
            unset($columns[1]);
        }
        return $columns;
    }
}

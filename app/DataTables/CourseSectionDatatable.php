<?php

namespace App\DataTables;

use App\Models\CourseSection;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Keyhanweb\Subsystem\DataTables\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class CourseSectionDatatable extends DataTable
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
                if (request('courseID')) {
                    $query->where('courseID', request('courseID'));
                }
                return $query;
            })
            ->editColumn('title', function ($model) {
                if (mb_strlen($model->title) > 60) {
                    return mb_substr($model->title, 0, 50, 'UTF-8') . '...';
                }
                return $model->title;
            })
            ->addColumn('episodes', function ($model) {
                return $this->episodesAction($model);
            })
            ->addColumn('course', function ($model) {
                if (mb_strlen($model->course->name) > 60) {
                    return mb_substr($model->course->name, 0, 50, 'UTF-8') . '...';
                }
                return $model->course->name;
            })
            ->addColumn('edit', function ($model) {
                return $this->actionEdit(route('admin.course.section.edit', $model->ID));
            })
            ->rawColumns(['episodes', 'edit',])
            ->setTotalRecords($query->count())
            ->addIndexColumn()
            ->orderColumn('ID', ':column $1')
            ->setRowId('ID');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(CourseSection $model): QueryBuilder
    {
        return $model->newQuery()
            ->with('course')
            ->withCount('episodes as episodesCount')
            ->orderBy('sortOrder', 'desc');
    }

    public function episodesAction($model): string
    {
        return '<div>
            <a class="btn btn-sm btn-info" href="' . route(
                'admin.course.section.episode.list',
                ['courseSectionID' => $model->ID]
            ) . '" >' . st('Show') . ' - ' . $model->episodesCount .
            '</a>';
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('#')->orderable(false),
            Column::make('course')->title(st('courseName')),
            Column::make('title')->title(st('Title'))->orderable(false),
            Column::make('sortOrder')->title(st('Sort Order')),
            Column::make('episodes')->title(st('Episodes'))->orderable(false),
            Column::make('edit')->title(st('Edit'))->orderable(false),
        ];
    }
}

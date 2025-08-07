<?php

namespace App\DataTables;

use App\Enums\CourseCategoryStatuses;
use App\Models\CourseCategory;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Keyhanweb\Subsystem\DataTables\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class CourseCategoryDatatable extends DataTable
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
                if (request()->filled('slug')) {
                    $query->where('slug', 'like', "%" . request('slug') . "%");
                }
                if (request()->filled('title')) {
                    $query->where('title', 'like', "%" . request('title') . "%");
                }
                if (request()->filled('status')) {
                    $query->where('status', request('status'));
                }
                return $query;
            })
            ->editColumn('photoSID', function ($model) {
                return $this->getImage($model->photoSID);
            })
            ->editColumn('status', function ($model) {
                return CourseCategoryStatuses::getTranslateFormat($model->status);
            })
            ->addColumn('edit', function ($model) {
                return $this->actionEdit(route('admin.courseCategory.edit', $model->ID));
            })
            ->rawColumns(['photoSID','edit'])
            ->setTotalRecords($query->count())
            ->addIndexColumn()
            ->orderColumn('ID', ':column $1')
            ->setRowId('ID');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(CourseCategory $model): QueryBuilder
    {
        return $model->newQuery()
            ->orderBy('ID', 'desc')
            ->select(
                'ID',
                'title',
                'description',
                'slug',
                'status',
                'sortOrder',
                'photoSID',
            );
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('#')->orderable(false),
            Column::make('photoSID')->title(st('Photo')),
            Column::make('title')->title(st('Title'))->orderable(false),
            Column::make('slug')->title(st('Slug'))->orderable(false),
            Column::make('status')->title(st('Status'))->orderable(false),
            Column::make('sortOrder')->title(st('Sort Order')),
            Column::make('description')->title(st('Description'))->orderable(false),
            Column::make('edit')->title(st('Edit'))->orderable(false),
        ];
    }
}

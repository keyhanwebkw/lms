<?php

namespace App\DataTables;

use App\Enums\CourseStatuses;
use App\Enums\CourseTypes;
use App\Models\Course;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Keyhanweb\Subsystem\DataTables\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class CourseDatatable extends DataTable
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
                if (request()->filled('ID')) {
                    $query->where('ID', request('ID'));
                }
                if (request()->filled('slug')) {
                    $query->where('slug', 'like', "%" . request('slug') . "%");
                }
                if (request()->filled('name')) {
                    $query->where('name', 'like', "%" . request('name') . "%");
                }
                if (request()->filled('startDate')) {
                    $query->where('startDate', '>=', $this->parseTimeStamp(request('startDate')));
                }
                if (request()->filled('endDate')) {
                    $query->where('endDate', '<=', $this->parseTimeStamp(request('endDate')));
                }
                if (request()->filled('teacherID')) {
                    $query->where('teacherID', request('teacherID'));
                }
                return $query;
            })
            ->editColumn('name', function ($model) {
                if (mb_strlen($model->name) > 50) {
                    return mb_substr($model->name, 0, 40, 'UTF-8') . '...';
                }
                return $model->name;
            })
            ->editColumn('type', function ($model) {
                return CourseTypes::getTranslateFormat($model->type);
            })
            ->editColumn('status', function ($model) {
                return CourseStatuses::getTranslateFormat($model->status);
            })
            ->editColumn('startDate', function ($model) {
                return $this->parseDate($model->startDate);
            })
            ->editColumn('endDate', function ($model) {
                return $this->parseDate($model->endDate);
            })
            ->editColumn('price', function ($model) {
                return empty($model->price) ? st('free') : $this->parseAmount($model->price);
            })
            ->addColumn('courseSection', function ($model) {
                return $this->courseSectionAction($model);
            })
            ->addColumn('faq', function ($model) {
                return $this->faqAction($model);
            })
            ->addColumn('edit', function ($model) {
                return $this->actionEdit(route('admin.course.edit', $model->ID));
            })
            ->addColumn('participants', function ($model) {
                return $this->participantsAction($model);
            })
            ->rawColumns(['courseSection', 'edit', 'faq', 'participants'])
            ->setTotalRecords($query->count())
            ->addIndexColumn()
            ->orderColumns(['ID'], ':column $1')
            ->setRowId('ID');
    }

    public function courseSectionAction($model): string
    {
        return '<div>
            <a class="btn btn-sm btn-info" href="' . route(
                'admin.course.section.list',
                ['courseID' => $model->ID]
            ) . '" >' . st('Show') . ' - ' . $model->courseSectionCount .
            '</a>';
    }

    public function faqAction($model)
    {
        return '<div>
            <a class="btn btn-sm btn-indigo" href="' . route(
                'admin.faq.list',
                ['relatedTo' => 'course', 'relatedID' => $model->ID]
            ) . '"' .
            '><i class="far fa-lg fa-eye"></i>&nbsp</a></div>';
    }

    public function participantsAction($model)
    {
        $btnText = match ($model->participantLimitation) {
            0 => $model->participants,
            default => "$model->participantLimitation/$model->participants",
        };

        $attendeesID = $model->attendees->pluck('pivot.userID')->toArray();
        $compressedAttendeesID = !empty($attendeesID) ? base64_encode(json_encode($attendeesID)) : null;

        $htmlAttr = ($model->participants == 0) ? 'style="cursor: not-allowed; opacity: 0.5;" onclick="return false;"' : '';

        return '<div>
            <a class="btn btn-sm btn-indigo" ' . $htmlAttr .' href="' . route(
                'admin.user.parent.list',
                ['IDs' => $compressedAttendeesID]
            ) . '"' .
            '>' . $btnText .'</a></div>';
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Course $model): QueryBuilder
    {
        return $model->newQuery()
            ->select(
                'ID',
                'name',
                'slug',
                'type',
                'startDate',
                'endDate',
                'price',
                'status',
                'participants',
                'participantLimitation',
            )
            ->withCount('courseSection as courseSectionCount')
            ->with('attendees:ID')
            ->orderBy('ID', 'desc');
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('#')->orderable(false),
            Column::make('name')->title(st('name'))->orderable(false),
            Column::make('type')->title(st('type'))->orderable(false),
            Column::make('startDate')->title(st('startDate'))->orderable(false),
            Column::make('endDate')->title(st('endDate'))->orderable(false),
            Column::make('price')->title(st('price')),
            Column::make('status')->title(st('status')),
            Column::make('participants')->title(st('participants')),
            Column::make('faq')->title(st('menu.Faq')),
            Column::make('courseSection')->title(st('Sections')),
            Column::make('edit')->title(st('Edit'))->orderable(false),
        ];
    }
}

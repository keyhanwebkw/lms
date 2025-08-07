<?php

namespace App\DataTables;

use App\Enums\CommentStatuses;
use App\Models\Article;
use App\Models\Comment;
use App\Models\Course;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Keyhanweb\Subsystem\DataTables\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class CommentDatatable extends DataTable
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
                if (request()->filled('parentID')) {
                    $query->where('parentID', request('parentID'));
                }
                if (request()->filled('ID')) {
                    $query->where('ID', request('ID'));
                }
                if (request()->filled('relatedTo')) {
                    $query->where('commentable_type', 'like', '%' . request('relatedTo'));
                }
                if (request()->filled('relatedName')) {
                    $relatedIDs = $this->getRelatedIDs();
                    $query->whereIn('commentable_id', $relatedIDs);
                }
                if (request()->filled('status')) {
                    $query->where('status', request('status'));
                }
                if (request()->filled('userID')) {
                    $query->where('userID', request('userID'));
                }
                match (request('replyStatus')) {
                    'answered' => $query->where('hasReply', true),
                    'unanswered' => $query->where('hasReply', false),
                    default => $query,
                };
                return $query;
            })
            ->editColumn('content', function ($model) {
                if (mb_strlen($model->content) > 60) {
                    return mb_substr($model->content, 0, 50, 'UTF-8') . ' ...';
                }
                return $model->content;
            })
            ->editColumn('status', function ($model) {
                return $this->statusAction($model);
            })
            ->editColumn('relatedTo', function ($model) {
                return $this->relatedToAction($model);
            })
            ->addColumn('replyStatus', function ($model) {
                return $this->replyStatusAction($model);
            })
            ->addColumn('edit', function ($model) {
                match ($model->status) {
                    'pending' => $result = $this->reviewAction($model),
                    default => $result = $this->actionEdit(route('admin.comment.edit', $model->ID)),
                };
                return $result;
            })
            ->addColumn('reply', function ($model) {
                return $this->replyAction($model);
            })
            ->rawColumns(['relatedTo', 'replyStatus', 'review', 'edit', 'status', 'reply'])
            ->setTotalRecords($query->count())
            ->addIndexColumn()
            ->orderColumn('ID', ':column $1')
            ->setRowId('ID');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Comment $model): QueryBuilder
    {
        return $model->newQuery()
            ->orderBy('ID', 'desc')
            ->select([
                'ID',
                'content',
                'commentable_type',
                'commentable_id',
                'hasReply',
                'parentID',
                'status',
            ])
            ->withCount('replies as repliesCount');
    }

    public function relatedToAction($model): string
    {
        $className = strtolower(class_basename($model->commentable_type));
        return '
                <div><a class="btn btn-sm btn-indigo" href="' . route(
                "admin.{$className}.list"
            ) . "?ID={$model->commentable_id}" . '"><i class="far fa-eye"></i>&nbsp' . st($className) . '</a></div>
                ';
    }

    public function replyStatusAction($model): string
    {
        if (!$model->repliesCount) {
            return '<div><a class="btn btn-sm btn-secondary" style="cursor: not-allowed; opacity: 0.5;" onclick="return false;" > ' . st(
                    'Unanswered'
                ) . ' </a></div>';
        }

        return '<div><a class="btn btn-sm btn-secondary" href="' . route(
                'admin.comment.list'
            ) . '?parentID=' . $model->ID . '" >' . st('Answers') . " - {$model->repliesCount}" . '</a></div>';
    }

    protected function getRelatedIDs(): array
    {
        return match (request('relatedTo')) {
            'Article' => Article::query()->where('title', 'like', '%' . request('relatedName') . '%')->pluck('ID')->toArray(),
            'Course' => Course::query()->where('name', 'like', '%' . request('relatedName') . '%')->pluck('ID')->toArray(),
            default => [],
        };
    }

    public function statusAction($model): string
    {
        match ($model->status){
            CommentStatuses::APPROVED->value => $htmlAttr = 'class="text-success"',
            CommentStatuses::REJECTED->value => $htmlAttr = 'class="text-danger"',
            CommentStatuses::PENDING->value => $htmlAttr = 'class="text-warning"',
            default => $htmlAttr = '',
        };

        return '<div><p ' . $htmlAttr . ' >' . CommentStatuses::getTranslateFormat($model->status) .'</p></div>' ;
    }

    public function reviewAction($model): string
    {
        return '<div><a class="btn btn-sm btn-success " href="' . route(
                'admin.comment.edit'
                ,
                $model->ID
            ) . '" >' . st('Review') . '</a></div>';
    }

    public function replyAction($model): string
    {
        return '<div><a class="btn btn-sm btn-info" href="' . route('admin.comment.create', $model->ID)
            . '" ><i class="far fa-reply fa-solid"></i>&nbsp</a></div>';
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('#')->orderable(false),
            Column::make('content')->title(st('content'))->orderable(false),
            Column::make('status')->title(st('status'))->orderable(false),
            Column::make('relatedTo')->title(st('Related to'))->orderable(false),
            Column::make('replyStatus')->title(st('Reply status'))->orderable(false),
            Column::make('edit')->title(st('Edit'))->orderable(false),
            Column::make('reply')->title(st('Reply'))->orderable(false),
        ];
    }
}

<?php

namespace App\DataTables;

use App\Enums\UserTypes;
use App\Models\User;
use Hekmatinasser\Verta\Verta;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Keyhanweb\Subsystem\DataTables\DataTable;
use Keyhanweb\Subsystem\Enums\Gender;
use Keyhanweb\Subsystem\Enums\UserStatus;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class ChildDatatable extends DataTable
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
                if (request()->filled('username')) {
                    $query->where('username', 'like', "%" . request('username') . "%");
                }
                if (request()->filled('name')) {
                    $query->where('name', 'like', "%" . request('name') . "%");
                }
                if (request()->filled('nationalCode')) {
                    $query->where('nationalCode', 'like', "%" . request('nationalCode') . "%");
                }
                if (request()->filled('status')) {
                    $query->where('status', request('status'));
                }
                if (request()->filled('birthYear')) {
                    $minBirthYear = Verta::parse(request('birthYear') . '/01/01')->timestamp;
                    $maxBirthYear = Verta::parse(request('birthYear') . '/12/29')->timestamp;
                    $query->whereBetween('birthDate', [$minBirthYear, $maxBirthYear]);
                }
                if (request()->filled('parentID')) {
                    $query->where('parentID', request('parentID'));
                }
                return $query;
            })
            ->editColumn('gender', function ($model) {
                return Gender::valuesTranslate()[$model->gender];
            })
            ->editColumn('nationalCode', function ($model) {
                return $this->optional($model->nationalCode);
            })
            ->addColumn('birthDate', function ($model) {
                return $this->parseDate($model->birthDate);
            })
            ->editColumn('status', function ($model) {
                return UserStatus::valuesTranslate()[$model->status] ?? '-';
            })
            ->addColumn('parent', function ($model) {
                return $this->parentsAction($model);
            })
            ->addColumn('show', function ($model) {
                return $this->actionShow(route('admin.user.child.show', $model->ID));
            })
            ->addColumn('edit', function ($model) {
                return $this->actionEdit(route('admin.user.child.edit', $model->ID));
            })
            ->addColumn('delete', function ($model) {
                return $this->actionDelete(route('admin.user.delete', $model->ID));
            })
            ->editColumn('avatarSID', function ($model) {
                return $this->getImage($model->avatarSID);
            })
            ->rawColumns(['edit', 'show', 'delete', 'parent', 'avatarSID'])
            ->setTotalRecords($query->count())
            ->addIndexColumn()
            ->orderColumns(['ID'], ':column $1')
            ->setRowId('ID');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(User $model): QueryBuilder
    {
        return $model->newQuery()
            ->select(
                'ID',
                'name',
                'username',
                'parentID',
                'gender',
                'status',
                'nationalCode',
                'birthDate',
                'avatarSID',
            )
            ->with('parent')
            ->where('type', UserTypes::Child->value);
    }

    public function parentsAction($model): string
    {
        return '<div>
            <a class="btn btn-sm btn-info" href="' . route('admin.user.parent.show', $model->parentID) . '" ' . '>
                <i class="far fa-eye"></i>&nbsp ' . st('Parent') . '</a>
        </div>';
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('#')->orderable(false),
            Column::make('avatarSID')->title(st('avatar'))->orderable(false),
            Column::make('name')->title(st('Name'))->orderable(false),
            Column::make('gender')->title(st('Gender'))->orderable(false),
            Column::make('username')->title(st('Username'))->orderable(false),
            Column::make('nationalCode')->title(st('National code'))->orderable(false),
            Column::make('birthDate')->title(st('Birth date'))->orderable(false),
            Column::make('status')->title(st('Status'))->orderable(false),
            Column::make('parent')->title(st('Parent'))->orderable(false),
            Column::make('show')->title(st('Show'))->orderable(false),
            Column::make('edit')->title(st('Edit'))->orderable(false),
            Column::make('delete')->title(st('Delete'))->orderable(false),
        ];
    }
}

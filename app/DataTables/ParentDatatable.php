<?php

namespace App\DataTables;

use App\Enums\UserTypes;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Keyhanweb\Subsystem\DataTables\DataTable;
use Keyhanweb\Subsystem\Enums\UserStatus;
use Keyhanweb\Subsystem\Models\RoleUser;
use Keyhanweb\Subsystem\Models\UserRole;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class ParentDatatable extends DataTable
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
                if (request()->filled('mobile')) {
                    $query->where('mobile', 'like', "%" . request('mobile') . "%");
                }
                if (request()->filled('name')) {
                    $query->where('name', 'like', "%" . request('name') . "%");
                }
                if (request()->filled('status')) {
                    $query->where('status', request('status'));
                }
                if (request()->filled('nationalCode')) {
                    $query->where('nationalCode', 'like', "%" . request('nationalCode') . "%");
                }
                if (request()->filled('family')) {
                    $query->where('family', 'like', "%" . request('family') . "%");
                }
                if (request()->filled('role')) {
                    $userIDs = UserRole::query()
                    ->where('roleID', request('role'))
                        ->pluck('userID')
                        ->toArray();
                    $query->whereIn('ID',$userIDs);
                }
                if (request()->filled('IDs')) {
                    $uncompressedAttendeesID = json_decode(base64_decode(request('IDs')));
                    $query->whereIn('ID', $uncompressedAttendeesID);
                }
                return $query;
            })
            ->editColumn('mobile', function ($model) {
                return preg_replace('/^\+98/', '0', $model->mobile);
            })
            ->editColumn('nationalCode', function ($model) {
                return $this->optional($model->nationalCode);
            })
            ->editColumn('balance', function ($model) {
                return $this->optional($model->balance);
            })
            ->editColumn('status', function ($model) {
                return UserStatus::valuesTranslate()[$model->status] ?? '-';
            })
            ->addColumn('children', function ($model) {
                return $this->childrenAction($model);
            })
            ->addColumn('show', function ($model) {
                return $this->actionShow(route('admin.user.parent.show', $model->ID));
            })
            ->editColumn('avatarSID', function ($model) {
                return $this->getImage($model->avatarSID);
            })
            ->addColumn('edit', function ($model) {
                return $this->actionEdit(route('admin.user.parent.edit', $model->ID));
            })
            ->addColumn('delete', function ($model) {
                return $this->actionDelete(route('admin.user.delete', $model->ID));
            })
            ->rawColumns(['edit', 'avatarSID', 'children', 'delete', 'show'])
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
                'family',
                'mobile',
                'nationalCode',
                'balance',
                'status',
                'avatarSID',
            )
            ->withCount('children as childrenCount')
            ->where('type', UserTypes::Parent->value);
    }

    public function childrenAction($model): string
    {
        return $model->childrenCount ? '<div>
            <a class="btn btn-sm btn-info" href="' . route('admin.user.child.list') . '?parentID=' . $model->ID . '" ' . '>
                <i class="far fa-users"></i>&nbsp ' . st('Children') . ' - ' . $model->childrenCount . '</a>
        </div>' : '<div>
            <button class="btn btn-sm btn-info"' . ' disabled' . '>
                <i class="far fa-users"></i>&nbsp ' . st('No child') . '</button>
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
            Column::make('family')->title(st('Family'))->orderable(false),
            Column::make('mobile')->title(st('Mobile'))->orderable(false),
            Column::make('nationalCode')->title(st('National code'))->orderable(false),
            Column::make('balance')->title(st('Balance'))->orderable(false),
            Column::make('status')->title(st('Status'))->orderable(false),
            Column::make('children')->title(st('Children'))->orderable(false),
            Column::make('show')->title(st('Show'))->orderable(false),
            Column::make('edit')->title(st('Edit'))->orderable(false),
            Column::make('delete')->title(st('Delete'))->orderable(false),
        ];
    }
}

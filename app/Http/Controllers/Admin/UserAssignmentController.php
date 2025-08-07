<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\UserAssignmentDatatable;
use App\Enums\NotificationTypes;
use App\Enums\UserAssignmentStatuses;
use App\Enums\UserExamStatuses;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Assignment\Check\UpdateRequest;
use App\Models\Assignment;
use App\Models\Notification;
use App\Models\User;
use App\Models\UserAssignment;

class UserAssignmentController extends Controller
{
    public function list(UserAssignmentDatatable $datatable)
    {
        $assignmentID = request('assignmentID');
        $assignment = Assignment::query()
            ->select(
                'ID',
                'title'
            )
            ->findOrFail($assignmentID);

        $userIDs = UserAssignment::query()
            ->where('assignmentID', $assignmentID)
            ->pluck('userID')
            ->toArray();

        $users = User::query()
            ->select(
                'ID',
                'name',
                'family'
            )
            ->whereIn('ID', $userIDs)
            ->get()
            ->pluck('fullname', 'ID');

        $statuses = UserAssignmentStatuses::valuesTranslate();

        return $datatable->render('admin.assignment.check.list', compact('assignment', 'statuses', 'users'));
    }

    public function show(UserAssignment $userAssignment)
    {
        $userAssignment->load(['content', 'content.storage', 'user']);

        $assignment = Assignment::query()
            ->select(
                'ID',
                'title',
                'minScoreToPass',
            )
            ->find($userAssignment->assignmentID);
        $userFullName = User::query()
            ->select(
                'ID',
                'name',
                'family',
            )
            ->find($userAssignment->userID)
            ->fullname;

        return view('admin.assignment.check.show', compact('userAssignment', 'assignment', 'userFullName'));
    }

    public function update(UpdateRequest $request, UserAssignment $userAssignment)
    {
        $data = $request->validated();

        $assignment = Assignment::query()
            ->findOrFail($userAssignment->assignmentID);

        if ($data['receivedScore'] >= $assignment->minScoreToPass && $data['status'] == 'rejected') {
            return back()->withErrors([st('Wrong assignment rejection')])->withInput();
        } elseif ($data['receivedScore'] < $assignment->minScoreToPass && $data['status'] == 'accepted'){
            return back()->withErrors([st('Wrong assignment acceptation')])->withInput();
        }

        $userAssignment->update($data);

        $user = User::find($userAssignment->userID);

        if ($userAssignment->status == UserAssignmentStatuses::Accepted->value) {
            Notification::send(
                $user->ID,
                st('Notif - Assignment - accepted title'),
                st('Notif - Assignment - accepted content', ['name' => $user->name, 'title' => $assignment->title]),
                NotificationTypes::Success->value,
            );
        } else {
            Notification::send(
                $user->ID,
                st('Notif - Assignment - rejected title'),
                st('Notif - Assignment - rejected content', ['name' => $user->name, 'title' => $assignment->title]),
                NotificationTypes::Warning->value,
            );
        }

        return redirect()->route('admin.assignment.check.list',['assignmentID' => $userAssignment->assignmentID])
            ->with('success', st('Operation done successfully'));
    }
}

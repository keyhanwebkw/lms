<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\Support\CloseRequest;
use App\Http\Requests\Api\Support\GetRequest;
use App\Http\Requests\Api\Support\ReplyRequest;
use App\Http\Requests\Api\Support\SendRequest;
use App\Http\Requests\Api\Support\TicketsRequest;
use App\Http\Requests\Api\Support\DepartmentsRequest;
use App\Models\SupportTicket;
use App\Models\SupportMessage;
use App\Models\SupportDepartment;

class SupportController extends ApiController
{
	public function tickets(TicketsRequest $request)
	{
		$data = $request->validated();
		$query = SupportTicket::Select([
			'ID',
			'title',
			'departmentID',
			'status',
			'updated',
		])
			->where('userID', '=', auth()->user()->ID);

		$limit = $data['showPerPage'];
		$skip = ($data['page'] - 1) * $limit;

		$tickets = $query->orderBy($data['orderBy'], $data['order'])
			->skip($skip)
			->take($limit)
			->get()
			->map(function ($ticket) {
				$ticket->statusLocalized = __('app.ticketStatus.' . $ticket->status);
				return $ticket;
			})
			->toArray();

		$departments = SupportDepartment::Select([
			'ID',
			'name',
			'slug',
		])->get()->toArray();

		return $this->success([
			'tickets' => $tickets,
			'departments' => $departments
		]);
	}

	public function departments(DepartmentsRequest $request)
	{
		$data = $request->validated();
		$query = SupportDepartment::Select([
			'ID',
			'name',
			'slug',
		])->where('isArchived', '=', 0)
			->where('status', '=', 'active');

		if (!empty($data['name'])) {
			$query->where('name', 'LIKE', "%{$data['name']}%");
		}

		if (!empty($data['slug'])) {
			$query->where('slug', 'LIKE', "%{$data['slug']}%");
		}

		$limit = $data['showPerPage'];
		$skip = ($data['page'] - 1) * $limit;

		$departments = $query->orderBy($data['orderBy'], $data['order'])
			->skip($skip)
			->take($limit)
			->get()
			->toArray();

		return $this->success([
			'departments' => $departments
		]);
	}

	public function get(GetRequest $request)
	{
		$data = $request->validated();
		$userID = auth()->user()->ID;

		$ticket = SupportTicket::select([
			'ID',
			'title',
			'departmentID',
			'updated',
			'status'
		])
			->where([
				'ID' => $data['ticketID'],
				'userID' => $userID
			])->first();
		if (!$ticket) {
			return $this->error(1002040201, __('app.Ticket not found'));
		}

		$department = SupportDepartment::where(['ID' => $ticket->departmentID])->first();
		if (!$department) {
			return $this->error(1002040201, __('app.Department not found'));
		}

		$messages = SupportMessage::where([
			'ticketID' => $data['ticketID'],
		])
			->orderBy('ID', 'ASC')
			->get()
			->toArray();

		$ticket = $ticket->toArray();
		$ticket['statusLocalized'] = __('app.ticketStatus.' . $ticket['status']);
		return $this->success([
			'ticket' => $ticket,
			'department' => $department->toArray(),
			'messages' => $messages
		]);
	}

	public function send(SendRequest $request)
	{
		$data = $request->validated();

		$department = SupportDepartment::where(['ID' => $data['departmentID']])->first();
		if (!$department) {
			return $this->error(1002040201, __('app.Department not found'));
		}

		$ticket = SupportTicket::create([
			'departmentID' => $data['departmentID'],
			'userID' => auth()->user()->ID,
			'title' => $data['title'],
			'status' => 'open',
		]);

		// save message
		$message = SupportMessage::create([
			'repliedMessageID' => 0,
			'ticketID' => $ticket->ID,
			'userID' => auth()->user()->ID,
			'responderUserID' => 0,
			'message' => $data['message'],
		]);

		// update userLastMessageID
		$ticket->userLastMessageID = $message->ID;
		$ticket->save();

		return $this->success();
	}

	public function reply(ReplyRequest $request)
	{
		$data = $request->validated();
		$userID = auth()->user()->ID;
		$department = SupportDepartment::where(['ID' => $data['departmentID']])->first();
		if (!$department) {
			return $this->error(1002040201, __('app.Department not found'));
		}

		$ticket = SupportTicket::where([
			'ID' => $data['ticketID'],
			'userID' => $userID
		])->first();
		if (!$ticket) {
			return $this->error(1002040201, __('app.Ticket not found'));
		}

		$message = SupportMessage::create([
			'repliedMessageID' => 0,
			'ticketID' => $ticket->ID,
			'userID' => auth()->user()->ID,
			'responderUserID' => 0,
			'message' => $data['message'],
		]);

		$ticket->status = $ticket->status == 'open' ? 'open' : 'inProgress';
		$ticket->userLastMessageID = $message->ID;
		$ticket->save();

		return $this->success();
	}

	public function close(CloseRequest $request)
	{
		$data = $request->validated();
		$userID = auth()->user()->ID;

		$ticket = SupportTicket::where([
			'ID' => $data['ticketID'],
			'userID' => $userID
		])->first();
		if (!$ticket) {
			return $this->error(1002040201, __('app.Ticket not found'));
		}
		$ticket->status = 'closed';
		$ticket->save();

		return $this->success();
	}

}
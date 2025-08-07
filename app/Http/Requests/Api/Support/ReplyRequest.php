<?php

namespace App\Http\Requests\Api\Support;

use App\Http\Requests\Api\ApiRequest;

class ReplyRequest extends ApiRequest
{

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'departmentID' => ['required', 'integer'],
			'ticketID' => ['required', 'integer'],
			'message' => ['required', 'string'],
		];
	}

	 /**
	 * Prepare the data for validation.
	 *
	 * @return void
	 */
	protected function prepareForValidation()
	{
		$this->merge([
			'message' => strip_tags($this->message)
		]);
	}
}

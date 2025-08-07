<?php

namespace App\Http\Requests\Api\Support;

use App\Http\Requests\Api\ApiRequest;

class SendRequest extends ApiRequest
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
			'title' => ['required', 'string', 'min:3'],
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
			'title' => strip_tags($this->title),
			'message' => strip_tags($this->message)
		]);
	}
}

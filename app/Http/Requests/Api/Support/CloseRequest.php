<?php
namespace App\Http\Requests\Api\Support;

use App\Http\Requests\Api\ApiRequest;

class CloseRequest extends ApiRequest
{

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'ticketID' => ['required', 'integer'],
		];
	}

}

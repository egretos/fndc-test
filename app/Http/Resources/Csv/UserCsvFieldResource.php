<?php

namespace App\Http\Resources\Csv;

use App\Models\CsvField;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;

/**
 * @mixin CsvField
 */
class UserCsvFieldResource extends JsonResource
{
	public static $wrap = 'addresses';
	
	public function toArray($request) {
		if (!isset($this->field_data['address'])) {
			return new MissingValue();
		}
		
		return [
			'address' => $this->field_data['address'],
			'validation_status' => $this->validation_status,
		];
	}
}
<?php

namespace App\Http\Resources\Csv;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCsvFieldsCollection extends ResourceCollection
{
	public $collects = UserCsvFieldResource::class;
	
	public static $wrap = 'addresses';
}
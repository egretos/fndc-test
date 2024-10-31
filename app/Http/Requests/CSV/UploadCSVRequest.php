<?php

namespace App\Http\Requests\CSV;

use App\Models\CsvUpload;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

class UploadCSVRequest extends FormRequest
{
	public function authorize(): true
	{
		return true;
	}
	
	public function rules(): array
	{
		return [
			'file' => [
				'required',
				'file',
				'mimes:csv',
			],
			'mappings' => [
				'required',
				'string'
			],
		];
	}
	
	public function getFile(): array|UploadedFile|null
	{
		return $this->file('file');
	}
	
	public function getMappings(): string
	{
		return $this->input('mappings');
	}
}
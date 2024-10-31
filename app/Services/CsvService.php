<?php

namespace App\Services;

use App\Jobs\VerifyCsvFieldJob;
use App\Models\CsvField;
use App\Models\CsvUpload;
use App\Models\User;
use App\Services\AddressVerification\AddressVerificationService;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use League\Csv\Reader;
use League\Csv\UnavailableStream;
use LogicException;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\File;
use Throwable;

class CsvService
{
	/**
	 * @throws Exception
	 */
	public function store(Authenticatable $user, File $file, string $mappings): CsvUpload {
		
		try {
			if (!($user instanceof User)) {
				throw new Exception('User must be an instance of '.User::class);
			}
			$filename = Uuid::uuid4()->toString().'.'.$file->getExtension();
			
			if ($file instanceof UploadedFile) {
				$filename =  Uuid::uuid4()->toString().'.'.$file->getClientOriginalExtension();
			}
			
			$file = $file->move(
				storage_path('csv_uploads/'),
				$filename
			);
			
			$csvUpload = new CsvUpload([
				'file_name' => $filename,
				'file_path' => $file->getPath(),
				'field_mapping' => json_encode($mappings),
				'uploaded_at' => now(),
			]);
			
			$csvUpload->userUploaded()->associate($user);
			
			$csvUpload->save();
			
			return $csvUpload;
		} catch (Exception $exception) {
			Log::error($exception->getMessage(), $exception->getTrace());
			
			throw $exception;
		}
	}
	
	/**
	 * @throws UnavailableStream
	 * @throws \League\Csv\Exception
	 * @throws Throwable
	 */
	public function verifyFile(CsvUpload $csvUpload): void
	{
		try {
			$filePath = $csvUpload->file_path.'/'.$csvUpload->file_name;
			
			$csv = Reader::createFromPath($filePath, 'r');
			$csv->setHeaderOffset(0);
			
			$fieldsToCreate = [];
			foreach ($csv as $fields) {
				foreach ($fields as $fieldName => $recordData) {
					if ($fieldName == CsvField::ADDRESS_FIELD_NAME) {
						
						$fieldsToCreate[] = [
							'field_data' => json_encode($fields),
							'validation_status' => CsvField::STATUS_PENDING,
							'csv_upload_id' => $csvUpload->id,
							'created_at' => now(),
							'updated_at' => now(),
						];
					}
				}
			}
			
			CsvField::query()->insert($fieldsToCreate);
			
			$csvUpload->load('csvFields');
			
			$batch = Bus::batch([])->dispatch();
			foreach ($csvUpload->csvFields as $csvField) {
				$batch->add(new VerifyCsvFieldJob($csvField));
			}
		} catch (Exception|Throwable $exception) {
			Log::error($exception->getMessage(), $exception->getTrace());
			throw $exception;
		}
	}
	
	/**
	 * @throws Exception
	 */
	public function verifyField(CsvField $csvField): CsvField
	{
		try {
			if (!$csvField->isPending()) {
				throw new LogicException('Field has already been verified');
			}
			
			$address = $csvField->field_data['address'];
			
			try {
				$addressVerificationService = app(AddressVerificationService::class);
				
				$isValid = $addressVerificationService->verifyAddress($address);
			} catch (Exception) {
				$csvField->update([
					'validation_status' => CsvField::STATUS_ERROR,
				]);
				return $csvField;
			}
			
			$csvField->update([
				'validation_status' => $isValid ? CsvField::STATUS_VALID: CsvField::STATUS_INVALID,
			]);
			
			return $csvField;
			
		} catch (Exception $exception) {
			Log::error($exception->getMessage(), $exception->getTrace());
			throw $exception;
		}
	}
}
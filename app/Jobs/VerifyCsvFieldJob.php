<?php

namespace App\Jobs;

use App\Models\CsvField;
use App\Models\CsvUpload;
use App\Services\CsvService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class VerifyCsvFieldJob implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
	
	private string $batchId;
	
	public function __construct(
		protected CsvField $csvField
	) {}
	
	public function withBatchId(string $batchId): static
	{
		$this->batchId = $batchId;
		
		return $this;
	}
	
	/**
	 * @throws Exception
	 */
	public function handle(CsvService $csvService): void
	{
		$csvService->verifyField($this->csvField);
	}
}
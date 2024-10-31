<?php

namespace App\Jobs;

use App\Models\CsvField;
use App\Models\CsvUpload;
use App\Services\CsvService;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class VerifyCsvUploadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
		 protected CsvUpload $csvUpload,
    ) {}
	
    public function handle(CsvService $csvService): void
    {
		$csvService->verifyFile($this->csvUpload);
    }
}

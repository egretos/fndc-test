<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CSV\UploadCSVRequest;
use App\Services\CsvService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CsvUploadController extends Controller
{
	public function __construct(
		private readonly CsvService $csvService
	)
	{
	
	}
	
    /** uploads the CSV file and its mappings
     * @param UploadCSVRequest $request
     * @return JsonResponse
     */
    public function upload(UploadCSVRequest $request): JsonResponse
    {
		try {
		    $this->csvService->store(
				Auth::user(),
				$request->getFile(),
				$request->getMappings()
		    );
			
		    return response()->json(['message' => 'File uploaded successfully.']);
	    } catch (Exception) {
		    return response()->json(['message' => 'Server Error'], 400);
	    }
    }
	
}

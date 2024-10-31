<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Csv\UserCsvFieldsCollection;
use Illuminate\Support\Facades\Auth;

class CsvFieldController extends Controller
{
    public function index()
    {
		$user = Auth::user();
		
		$user->load('csvFields');
		
		return UserCsvFieldsCollection::make($user->csvFields);
    }
}

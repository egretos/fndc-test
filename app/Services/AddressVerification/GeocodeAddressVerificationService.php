<?php

namespace App\Services\AddressVerification;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class GeocodeAddressVerificationService implements AddressVerificationService
{
	/**
	 * @throws GuzzleException
	 */
	function verifyAddress($address): bool
	{
		$client = new Client();
		$apiKey = env('GEOAPIFY_API_KEY');
		
		try {
			$response = $client->request('GET', 'https://api.geoapify.com/v1/geocode/search', [
				'query' => [
					'text' => $address,
					'apiKey' => $apiKey,
					'format' => 'json',
				],
			]);
			
			$body = json_decode($response->getBody(), true);
			
			return (!empty($body['results']));
		} catch (Exception $exception) {
			Log::error($exception->getMessage(), $exception->getTrace());
			throw $exception;
		} catch (GuzzleException $exception) {
			Log::critical($exception->getMessage(), $exception->getTrace());
			throw $exception;
		}
	}
}
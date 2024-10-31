<?php

namespace App\Services\AddressVerification;

interface AddressVerificationService
{
	function verifyAddress($address): bool;
}
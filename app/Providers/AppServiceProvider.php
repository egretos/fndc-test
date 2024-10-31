<?php

namespace App\Providers;

use App\Services\AddressVerification\AddressVerificationService;
use App\Services\AddressVerification\GeocodeAddressVerificationService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
		
		App::bind(AddressVerificationService::class, GeocodeAddressVerificationService::class);
    }
}

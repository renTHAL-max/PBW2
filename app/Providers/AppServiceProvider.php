<?php

namespace App\Providers;

use App\Models\Rental;
use App\Observers\RentalObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Rental::observe(RentalObserver::class);
    }
}
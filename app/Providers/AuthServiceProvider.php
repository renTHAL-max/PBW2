<?php

namespace App\Providers;

use App\Models\Vehicle;
use App\Models\Rental;
use App\Models\Customer;
use App\Policies\VehiclePolicy;
use App\Policies\RentalPolicy;
use App\Policies\CustomerPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Vehicle::class => VehiclePolicy::class,
        Rental::class => RentalPolicy::class,
        Customer::class => CustomerPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\DeliveryService;
use App\Services\NovaPoshtaService;

class DeliveryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(DeliveryService::class, NovaPoshtaService::class);
    }
}

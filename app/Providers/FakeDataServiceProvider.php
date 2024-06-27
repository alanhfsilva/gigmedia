<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\FakeDataService;

class FakeDataServiceProvider extends ServiceProvider
{
    /**
     * Register application service.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(FakeDataService::class, function ($app) {
            return new FakeDataService();
        });
    }
}

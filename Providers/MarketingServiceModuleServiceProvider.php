<?php

namespace App\Modules\MarketingService\Providers;

use App\Modules\MarketingService\Repositories\MarketingServiceRepository;
use App\Modules\MarketingService\Services\MarketingServices;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class MarketingServiceModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(MarketingServiceRepository::class, function ($app) {
            return new MarketingServiceRepository(new \App\Modules\MarketingService\Models\MarketingService());
        });
        $this->app->alias(MarketingServiceRepository::class, 'modules.marketing-service.repository');

        $this->app->singleton(MarketingServices::class, fn() => new MarketingServices());
        $this->app->alias(MarketingServices::class, 'modules.marketing-service.service');
    }

    public function boot(): void
    {
        $enabled = (bool) (Config::get('modules.marketing-service.enabled', true));
        if (! $enabled) {
            return;
        }

        // Routes are loaded from routes/admin_new.php
        // $this->loadRoutesFrom(__DIR__ . '/../routes/admin.php');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Publish JSX views
        $this->publishes([
            __DIR__ . '/../resources/js/pages/admin/marketing' => resource_path('js/pages/admin/marketing'),
        ], 'marketing-service-views');
    }
}


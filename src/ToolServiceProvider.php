<?php

namespace DigitalCloud\NovaResourceStatus;

use DigitalCloud\NovaResourceStatus\Resources\Status;
use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use DigitalCloud\NovaResourceStatus\Http\Middleware\Authorize;

class ToolServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'nova-resource-status');

        if (! class_exists('CreateStatusesTable')) {
            $timestamp = date('Y_m_d_His', time());
            $this->publishes([
                __DIR__.'/../database/migrations/create_statuses_table.php.stub' => database_path('migrations/'.$timestamp.'_create_statuses_table.php'),
            ], 'migrations');
        }
        $this->publishes([
            __DIR__.'/../config/nova-resource-status.php' => config_path('nova-resource-status.php'),
        ], 'config');

        $this->app->booted(function () {
            $this->routes();
            Nova::resources([
                Status::class
            ]);
        });

        Nova::serving(function (ServingNova $event) {
            Nova::script('nova-resource-status-field', __DIR__.'/../dist/js/field.js');
        });



    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova', Authorize::class])
                ->prefix('nova-vendor/nova-resource-status')
                ->group(__DIR__.'/../routes/api.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        $this->mergeConfigFrom(__DIR__.'/../config/nova-resource-status.php', 'nova-resource-status');
        $this->app->singleton(StatusObserver::class, function () {
            return new StatusObserver();
        });

    }
}

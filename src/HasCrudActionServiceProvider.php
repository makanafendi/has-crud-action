<?php

namespace SmartPOS\HasCrudAction;

use Illuminate\Support\ServiceProvider;

class HasCrudActionServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('has-crud-action.php'),
            ], 'config');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'has-crud-action');

        $this->app->singleton('has-crud-action', function () {
            return new HasCrudAction;
        });
    }
}

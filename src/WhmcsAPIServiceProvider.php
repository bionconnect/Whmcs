<?php

namespace BionConnection\WhmcsAPI;

use Illuminate\Support\ServiceProvider;

class WhmcsAPIServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'bionconnection');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'bionconnection');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/whmcsapi.php', 'whmcsapi');

        // Register the service the package provides.
        $this->app->singleton('whmcsapi', function ($app) {
            return new WhmcsAPI;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['whmcsapi'];
    }
    
    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/whmcsapi.php' => config_path('whmcsapi.php'),
        ], 'whmcsapi.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/bionconnection'),
        ], 'whmcsapi.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/bionconnection'),
        ], 'whmcsapi.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/bionconnection'),
        ], 'whmcsapi.views');*/

        // Registering package commands.
        // $this->commands([]);
    }
}

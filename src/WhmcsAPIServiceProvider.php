<?php

namespace BionConnection\WhmcsAPI;

use Illuminate\Support\ServiceProvider;
use Gufy\Whmcs\Whmcs;

class WhmcsAPIServiceProvider extends ServiceProvider {

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    protected $defer = false;

    public function boot() {
        
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register() {
       

        // Register the service the package provides.
        $this->app->singleton('whmcsapi', function () {
            $whmcs = new Whmcs();
            \Config::set('whmcs.url', config('whmcsapi.url'));
            \Config::set('whmcs.password', config('whmcsapi.password'));
            \Config::set('whmcs.username', config('whmcsapi.username'));

            return new WhmcsAPI($whmcs);
        });

        $this->app->booting(function() {

            $loader = \Illuminate\Foundation\AliasLoader::getInstance();

            $loader->alias('WhmcsApi', 'BionConnection\WhmcsAPI\Facades\WhmcsAPI');
        });

        $this->publishes([
            dirname(__FILE__) . '/../config/whmcsapi.php' => config_path('whmcs.php'),
        ]);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides() {
        return ['whmcsapi'];
    }

}
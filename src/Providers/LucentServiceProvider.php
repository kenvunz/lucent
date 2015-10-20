<?php

namespace Gladeye\Lucent\Providers;

use Illuminate\Support\ServiceProvider;

class LucentServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('lucent.template', function($app) {
            return $app->make('Gladeye\Lucent\Template');
        });
    }
}

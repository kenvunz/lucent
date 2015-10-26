<?php
namespace Gladeye\Lucent\Providers;

use Gladeye\Lucent\Extensions\Blade as BladeExtension;
use Illuminate\Support\ServiceProvider;

class LucentServiceProvider extends ServiceProvider {
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        $this->app->singleton('lucent.routes', function ($app) {
            return __DIR__ . '/../Http/routes.php';
        });

        $this->app->singleton('lucent.env', function ($app) {
            return $app->make('Gladeye\Lucent\Wp\Environment');
        });

        $this->app->singleton('lucent.template', function ($app) {
            return $app->make('Gladeye\Lucent\Wp\Template');
        });

        $this->app->singleton('lucent.command.assets', function ($app) {
            return $app->make('Gladeye\Lucent\Commands\Assets');
        });

        $this->app->singleton('lucent.command.bower', function ($app) {
            return $app->make('Gladeye\Lucent\Commands\Bower');
        });

        $this->commands('lucent.command.assets', 'lucent.command.bower');
    }

    public function boot() {
        $app = $this->app;

        $app->group(['namespace' => 'Gladeye\Lucent'], function ($app) {
            require __DIR__ . '/../Wp/actions.php';
        });

        // even tho we don't use the `$view` but we have to `make` it here
        // so the `blade.compiler` can be available for us
        $view = $app->make('view');
        $blade = $app->make('blade.compiler');

        // Extend Blade with custom Wordpress directives
        BladeExtension::attach($app, $blade);
    }

    public function provides() {
        return [
            'lucent.routes',
            'lucent.env',
            'lucent.template',
            'lucent.command.assets',
            'lucent.command.bower',
        ];
    }
}

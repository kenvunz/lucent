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

        $this->app->singleton('Gladeye\Lucent\Wp\Environment', function ($app) {
            $whitelist = [
                'is_404',
                'is_search',
                'is_front_page',
                'is_home',
                'is_post_type_archive',
                'is_tax',
                'is_attachment',
                'is_single',
                'is_page',
                'is_singular',
                'is_category',
                'is_tag',
                'is_author',
                'is_date',
                'is_archive',
                'is_paged',
                'is_admin',

                'get_query_var',
                'get_post_type_object',
                'get_queried_object_id',
                'get_page_template_slug',
                'validate_file',
                'is_comments_popup',
                'get_queried_object'
            ];

            return new \Gladeye\Lucent\Wp\Environment($whitelist);
        });

        $this->app->alias('Gladeye\Lucent\Wp\Environment', 'lucent.env');

        $this->app->singleton('lucent.template', function ($app) {
            return $app->make('Gladeye\Lucent\Wp\Template');
        });
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
            'lucent.template'
        ];
    }
}

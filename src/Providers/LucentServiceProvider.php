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
        // Register services
        $this->app->singleton('lucent.template', function($app) {
            return $app->make('Gladeye\Lucent\Template');
        });
    }

    public function boot() {

        add_action('wp', function() {
            //Share all WP globals to all views
            $vars = apply_filters('lucent_template_file_globals',
                array('posts', 'post', 'wp_did_header', 'wp_did_template_redirect', 'wp_query', 'wp_rewrite', 'wpdb', 'wp_version', 'wp', 'id', 'comment', 'user_ID'));

            if (is_array($vars)) {
                $shares = [];
                foreach ($vars as $global_var) {
                    global $$global_var;
                    $share[$global_var] = $$global_var;
                }

                view()->share($share);
            }
        });
    }
}

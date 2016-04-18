<?php

namespace Gladeye\Lucent\Middleware;

use Closure;
use Gladeye\Lucent\Wp\Environment;
use Gladeye\Lucent\Wp\Template;

class WordpressMiddleware {

    protected $env;
    protected $template;

    public function __construct(Environment $env, Template $template) {
        $this->env = $env;
        $this->template = $template;
    }

    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if($response->status() === 404) {
            if($this->env->is_admin()) return $response;

            global $wp_query;

            if($wp_query->is_404()) return $response;

            return view($this->template->get());
        }
    }
}

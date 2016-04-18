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

        global $wp_query;

        if($response->status() === 404 && !$wp_query->is_404() && !$this->env->is_admin()) {
            return view($this->template->get());
        }

        return $response;
    }
}

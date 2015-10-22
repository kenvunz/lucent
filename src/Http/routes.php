<?php

$app->get('{any:.*}', function() use ($app) {
    $env = $app->make('lucent.env');
    $template = $app->make('lucent.template');

    if($env->is_admin()) return;

    global $wp_query;

    if($wp_query->is_404()) $app->abort(404);
    return view($template->get());
});

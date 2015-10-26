<?php

add_action('wp', function () use ($app) {
    //Share all WP globals to all views
    $vars = apply_filters('lucent_template_file_globals',
        array('posts', 'post', 'wp_did_header', 'wp_did_template_redirect', 'wp_query',
            'wp_rewrite', 'wpdb', 'wp_version', 'wp', 'id', 'comment', 'user_ID'));

    if (is_array($vars)) {
        $shares = [];
        foreach ($vars as $global_var) {
            global $$global_var;
            $share[$global_var] = $$global_var;
        }

        view()->share($share);
    }
});

add_action('admin_menu', function () {
    $remove_items = apply_filters('lucent_remove_admin_menu_item', ['themes.php']);

    foreach ($remove_items as $item) {
        remove_menu_page($item);
    }
});

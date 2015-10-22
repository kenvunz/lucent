<?php
namespace Gladeye\Lucent\Wp;

class Environment {

    protected $whitelist = [
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

    public function addFunction($name) {
        if(array_search($name, $this->whitelist) !== false) return;
        $this->whitelist[] = $name;
    }

    public function __call($method, $args) {
        if(array_search($method, $this->whitelist) === false)
            throw new \BadFunctionCallException("`{$method}` is not allowed to be called via this. Use `addFunction('{$method}')` to add it in");

        switch (count($args)) {
            case 0:
                return $method();

            case 1:
                return $method($args[0]);

            case 2:
                return $method($args[0], $args[1]);

            case 3:
                return $method($args[0], $args[1], $args[2]);

            case 4:
                return $method($args[0], $args[1], $args[2], $args[3]);

            default:
                return call_user_func_array($method, $args);
        }
    }
}

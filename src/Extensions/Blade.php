<?php
namespace Gladeye\Lucent\Extensions;

use Illuminate\Container\Container;
use Illuminate\View\Compilers\BladeCompiler;

class Blade {

    protected static $directives = [
        "Gladeye\Lucent\Directives\Loop",
    ];

    public static function attach(Container $container, BladeCompiler $blade) {
        $directives = static::$directives;

        if (function_exists('apply_filters')) {
            $directives = apply_filters('lucent_blade_directives', $directives);
        }

        foreach ($directives as $key => $directive) {
            $instance = $container->make($directive);
            $instance->extendTo($blade);
        }
    }
}

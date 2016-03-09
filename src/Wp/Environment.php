<?php
namespace Gladeye\Lucent\Wp;

class Environment {

    protected $whitelist = [];

    public function __construct($whitelist = []) {
        if(!empty($whitelist)) $this->whitelist = $whitelist;
    }

    public function addFunction($name) {
        if (array_search($name, $this->whitelist) !== false) {
            return;
        }

        $this->whitelist[] = $name;
    }

    public function __call($method, $args) {;
        if (array_search($method, $this->whitelist) === false) {
            throw new \BadFunctionCallException("`{$method}` is not allowed to be called via this. Use `addFunction('{$method}')` to add it in");
        }

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

<?php
namespace Gladeye\Lucent\Commands;

use Illuminate\Console\Command;

class Base extends Command {

    public function option($key = null, $default = null) {
        $value = parent::option($key);
        return is_null($value) ? $default : $value;
    }

    public function argument($key = null, $default = null) {
        $value = parent::argument($key);
        return is_null($value) ? $default : $value;
    }

    /**
     * Create a file/folders by the given path
     *
     * @param      string  $file     file name
     * @param      string  $content  content of the file
     */
    protected function makeFile($path, $content = null) {
        $info = pathinfo($path);

        $this->file->makeDirectory($info['dirname'], 0755, true, true);
        $this->file->put($path, $content);
    }
}

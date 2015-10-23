<?php
namespace Gladeye\Lucent\Commands;

use Gladeye\Lucent\Filesystem\Filesystem;

class Bower extends Base {

    protected $name = 'lucent:bower';

    protected $signature = 'lucent:bower {--directory=resources/assets/bower}';

    protected $description = 'Create .bowerrc file';

    protected $file;

    public function __construct(Filesystem $file) {
        parent::__construct();

        $this->file = $file;
    }

    public function handle() {
        $content = "{ directory: \"{$this->option('directory')}\"}";
        $file = $this->getLaravel()->basePath('.bowerrc');

        $this->makeFile($file, $content);

        $this->info('.bowerrc file created successfully');
    }
}

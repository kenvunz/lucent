<?php
namespace Gladeye\Lucent\Commands;

use Gladeye\Lucent\Filesystem\Filesystem;

class Assets extends Base {

    protected $name = 'lucent:assets';

    protected $signature = 'lucent:assets
                            {--exclude= : List of folders to be ignored, comma delimited}
                            {--parent=resources : Specify parent folder from app base path}
                            {--public=public : Specify public folder}
                            {--no-symlink : Specify if creating symlink should be ignored}';

    protected $description = 'Create an assets skeleton folder';

    protected $folders = [
        "styles",
        "scripts",
        "fonts",
        "images",
        "videos",
        "documents",
        "compiled",
    ];

    protected $file;

    public function __construct(Filesystem $file) {
        parent::__construct();

        $this->file = $file;
    }

    public function handle() {
        $folders = $this->option('exclude', []);
        if (is_string($folders)) {
            $folders = array_map('trim', explode(',', $folders));
        }

        $folders = array_diff($this->folders, $folders);

        $base = $this->getLaravel()->basePath($this->option('parent', 'resources'));

        if (empty($folders)) {
            $build = [$base . '/assets/.gitkeep'];
        } else {
            $build = array_map(function ($value) use ($base) {
                return $base . '/assets/' . $value . '/.gitkeep';
            }, $folders);
        }

        foreach ($build as $path) {
            $this->makeFile($path);
        }

        if (!$this->option('no-symlink')) {
            $from = $base . '/assets';
            $to = $this->getLaravel()->basePath($this->option('public'));

            $this->file->symlink($this->file->relativePath($to, $from), $to . '/assets', true);
        }

        $this->info('assets skeleton folder created successfully');
    }

    public function getFolders() {
        return $this->folders;
    }
}

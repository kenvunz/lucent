<?php
use Illuminate\Console\Application as Artisan;
use Laravel\Lumen\Application;
use Laravel\Lumen\Testing\TestCase as Base;

class TestCase extends Base {

    protected $artisan;

    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication() {
        $app = new Application(realpath(__DIR__ . '/../'));
        $app->singleton(
            Illuminate\Contracts\Console\Kernel::class,
            Kernel::class
        );
        return $app;
    }

    public function artisan($command, $parameters = []) {
        return $this->code = $this->getArtisan()->call($command, $parameters);
    }

    /**
     * Register the package's custom Artisan commands.
     *
     * @param  array|mixed  $commands
     * @return void
     */
    public function getArtisan() {
        if (is_null($this->artisan)) {
            $this->artisan = new Artisan(
                $this->app, $this->app->make('events'), $this->app->version()
            );
        }

        return $this->artisan;
    }
}

use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {

    protected $commands = [
        //
    ];
}

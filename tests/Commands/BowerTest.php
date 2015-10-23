<?php
namespace Gladeye\Lucent\Commands;

use Gladeye\Lucent\Commands\Bower as BowerCommand;
use Mockery as m;
use TestCase;

class BowerTest extends TestCase {

    public function testBowerCommandRanAsExpected() {
        list($instance, $file) = $this->getInstance();

        $file->shouldReceive('makeDirectory')->times(1);
        $file->shouldReceive('put')->times(1)->with(
            $this->app->basePath('.bowerrc'),
            '{ directory: "resources/assets/bower"}'
        );

        $output = $this->artisan('lucent:bower');
    }

    public function testBowerWithDirectoryFlagCommandRanAsExpected() {
        list($instance, $file) = $this->getInstance();

        $file->shouldReceive('makeDirectory')->times(1);
        $file->shouldReceive('put')->times(1)->with(
            $this->app->basePath('.bowerrc'),
            '{ directory: "foo/bower"}'
        );

        $output = $this->artisan('lucent:bower', [
            '--directory' => 'foo/bower',
        ]);
    }

    public function getInstance() {
        $file = m::mock('Gladeye\Lucent\Filesystem\Filesystem');
        $instance = new BowerCommand($file);

        $this->getArtisan()->add($instance);
        return [$instance, $file];
    }
}

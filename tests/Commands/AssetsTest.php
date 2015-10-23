<?php
namespace Gladeye\Lucent\Commands;

use Gladeye\Lucent\Commands\Assets as AssetsCommand;
use Mockery as m;
use TestCase;

class AssetsTest extends TestCase {

    public function testAssetsCommandRanAsExpected() {
        list($instance, $file) = $this->getInstance();
        $length = count($instance->getFolders());

        $file->shouldReceive('makeDirectory')->times($length);
        $file->shouldReceive('put')->times($length);
        $file->shouldReceive('relativePath')->times(1);
        $file->shouldReceive('symlink')->times(1);

        $output = $this->artisan('lucent:assets');
    }

    public function testAssetsCommandWithExcludeFlagRanAsExpected() {
        list($instance, $file) = $this->getInstance();
        $length = count($instance->getFolders());

        $file->shouldReceive('makeDirectory')->times($length - 2);
        $file->shouldReceive('put')->times($length - 2);
        $file->shouldReceive('relativePath')->times(1);
        $file->shouldReceive('symlink')->times(1);

        $output = $this->artisan('lucent:assets', [
            '--exclude' => 'images, scripts',
        ]);
    }

    public function testAssetsCommandWithParentFlagRanAsExpected() {
        list($instance, $file) = $this->getInstance();
        $length = count($instance->getFolders());

        $file->shouldReceive('makeDirectory')->times($length);
        $file->shouldReceive('put')->times($length);
        $file->shouldReceive('relativePath')->times(1)->passthru();
        $file->shouldReceive('symlink')->times(1)->with(
            '../foo/assets',
            $this->app->basePath('public/assets'),
            true
        );

        $output = $this->artisan('lucent:assets', [
            '--parent' => 'foo',
        ]);
    }

    public function testAssetsCommandWithPublicFlagRanAsExpected() {
        list($instance, $file) = $this->getInstance();
        $length = count($instance->getFolders());

        $file->shouldReceive('makeDirectory')->times($length);
        $file->shouldReceive('put')->times($length);
        $file->shouldReceive('relativePath')->times(1)->passthru();
        $file->shouldReceive('symlink')->times(1)->with(
            '../resources/assets',
            $this->app->basePath('foo/assets'),
            true
        );

        $output = $this->artisan('lucent:assets', [
            '--public' => 'foo',
        ]);
    }

    public function testAssetsCommandWithNoSymlinkFlagRanAsExpected() {
        list($instance, $file) = $this->getInstance();
        $length = count($instance->getFolders());

        $file->shouldReceive('makeDirectory')->times($length);
        $file->shouldReceive('put')->times($length);
        $file->shouldReceive('relativePath')->never();
        $file->shouldReceive('symlink')->never();

        $output = $this->artisan('lucent:assets', [
            '--no-symlink' => 1,
        ]);
    }

    public function getInstance() {
        $file = m::mock('Gladeye\Lucent\Filesystem\Filesystem');
        $instance = new AssetsCommand($file);

        $this->getArtisan()->add($instance);
        return [$instance, $file];
    }
}

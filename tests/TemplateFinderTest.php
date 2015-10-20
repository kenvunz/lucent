<?php
namespace Gladeye\Lucent;

use TestCase;
use Mockery as m;

class TemplateFinderTest extends TestCase {

    public function testFilterReturnAsExpected() {
        list($instance, $view) = $this->getInstance();

        $view->shouldReceive('exists')->once()->with('index')->andReturn(true)
            ->shouldReceive('getExtensions')->andReturn(['blade.php' => 'blade', 'php' => 'php']);

        $this->assertEquals($instance->filter(['index']), 'index');
    }

    public function testFilterWithIrregularTemplatesReturnAsExpected() {
        list($instance, $view) = $this->getInstance();

        $view->shouldReceive('exists')->once()->with('index')->andReturn(true)
            ->shouldReceive('getExtensions')->andReturn(['blade.php' => 'blade', 'php' => 'php']);

        $this->assertEquals($instance->filter(['index.blade.php']), 'index');
    }

    public function testFindReturnAsExpectedWithoutSecondArgument() {
        list($instance, $view) = $this->getInstance();

        $view->shouldReceive('exists')->once()->with('index')->andReturn(true)
            ->shouldReceive('getExtensions')->andReturn(['blade.php' => 'blade', 'php' => 'php']);

        $this->assertEquals($instance->find('index'), 'index');
    }

    public function testFindReturnAsExpectedWithSecondArgument() {
        list($instance, $view) = $this->getInstance();

        $view->shouldReceive('exists')->twice()->andReturn(false, true)
            ->shouldReceive('getExtensions')->andReturn(['blade.php' => 'blade', 'php' => 'php']);

        $this->assertEquals($instance->find('index', ['index1', 'index2']), 'index2');
    }

    protected function getInstance() {
        $view = m::mock('Illuminate\Contracts\View\Factory')
            ->shouldIgnoreMissing();

        $instance = new TemplateFinder($view);

        return [$instance, $view];
    }
}

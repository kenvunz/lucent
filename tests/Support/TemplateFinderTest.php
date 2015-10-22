<?php
namespace Gladeye\Lucent\Support;

use Mockery as m;
use TestCase;

class TemplateFinderTest extends TestCase {

    public function testFilterReturnAsExpected() {
        list($instance, $view) = $this->getInstance();

        $view->shouldReceive('exists')->once()->with('index')->andReturn(true)
            ->shouldReceive('getExtensions')->andReturn(['blade.php' => 'blade', 'php' => 'php']);

        $this->assertEquals('index', $instance->filter(['index']));
    }

    public function testFilterWithIrregularTemplatesReturnAsExpected() {
        list($instance, $view) = $this->getInstance();

        $view->shouldReceive('exists')->once()->with('index')->andReturn(true)
            ->shouldReceive('getExtensions')->andReturn(['blade.php' => 'blade', 'php' => 'php']);

        $this->assertEquals('index', $instance->filter(['index.blade.php']));
    }

    public function testFindReturnAsExpectedWithoutSecondArgument() {
        list($instance, $view) = $this->getInstance();

        $view->shouldReceive('exists')->once()->with('index')->andReturn(true)
            ->shouldReceive('getExtensions')->andReturn(['blade.php' => 'blade', 'php' => 'php']);

        $this->assertEquals('index', $instance->find('index'));
    }

    public function testFindReturnAsExpectedWithSecondArgument() {
        list($instance, $view) = $this->getInstance();

        $view->shouldReceive('exists')->twice()->andReturn(false, true)
            ->shouldReceive('getExtensions')->andReturn(['blade.php' => 'blade', 'php' => 'php']);

        $this->assertEquals('index2', $instance->find('index', ['index1', 'index2']));
    }

    protected function getInstance() {
        $view = m::mock('Illuminate\Contracts\View\Factory')
            ->shouldIgnoreMissing();

        $instance = new TemplateFinder($view);

        return [$instance, $view];
    }
}

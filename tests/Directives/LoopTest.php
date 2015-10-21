<?php
namespace Gladeye\Lucent\Directives;

use Illuminate\Container\Container;
use Illuminate\View\Compilers\BladeCompiler;
use TestCase;

class LoopTest extends TestCase {

    public function __construct() {
        parent::__construct();

        $app = new Container();
        $files = $app->make('Illuminate\Filesystem\Filesystem');
        $blade = new BladeCompiler($files, __DIR__.'/../../tmp');

        $instance = new Loop();
        $instance->extendTo($blade);

        $this->blade = $blade;
    }

    public function testWpLoopDirectiveWithoutExpressionCompiledAsExpected() {
        $actual = $this->blade->compileString("@wploop foo @endwploop");
        $expected = "<?php if(have_posts()): while(have_posts()): the_post(); ?> foo <?php endwhile; endif; ?>";

        $this->assertEquals($expected, $actual);
    }

    public function testWpLoopDirectiveWithoutExpressionWithWpEmptyCompiledAsExpected() {
        $actual = $this->blade->compileString("@wploop foo @wpempty empty @endwploop");
        $expected = "<?php if(have_posts()): while(have_posts()): the_post(); ?> foo <?php endwhile; else: ?> empty <?php endif; ?>";

        $this->assertEquals($expected, $actual);
    }

    public function testWpLoopDirectiveWithExpressionCompiledAsExpected() {
        $actual = $this->blade->compileString("@wploop(['post_type' => 'post']) foo @endwploop");
        $expected = "<?php \$__query = new Wp_Query(['post_type' => 'post']); ?> <?php if(\$__query->have_posts()): while(\$__query->have_posts()): \$__query->the_post(); ?> foo <?php endwhile; endif; wp_reset_postdata(); ?>";

        $this->assertEquals($expected, $actual);
    }

    public function testWithExpressionWithWpEmptyCompiledAsExpected() {
        $actual = $this->blade->compileString("@wploop(['post_type' => 'post']) foo @wpempty empty @endwploop");
        $expected = "<?php \$__query = new Wp_Query(['post_type' => 'post']); ?> <?php if(\$__query->have_posts()): while(\$__query->have_posts()): \$__query->the_post(); ?> foo <?php endwhile; else: ?> empty <?php endif; wp_reset_postdata(); ?>";

        $this->assertEquals($expected, $actual);
    }

    /**
     * @expectedException DomainException
     */
    public function testTheDirectiveRaisedDomainException() {
        $this->blade->compileString("@the('title')");
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testTheDirectiveRaisedInvalidArgumentException() {
        $this->blade->compileString("@wploop @the @endwploop");
    }

    public function testTheDirectiveCompiledAsExpected() {
        $actual = $this->blade->compileString("@wploop @the('title') @endwploop");
        $expected = "<?php if(have_posts()): while(have_posts()): the_post(); ?> <?php the_title(); ?> <?php endwhile; endif; ?>";

        $this->assertEquals($expected, $actual);
    }
}

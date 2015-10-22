<?php
namespace Gladeye\Lucent\Directives;

use Illuminate\View\Compilers\BladeCompiler;
use InvalidArgumentException;

class Loop {

    protected $hasStarted = false;
    protected $hasElse = false;
    protected $startWithExpression = true;

    public function extendTo(BladeCompiler $blade) {
        $blade->directive('wploop', $this->start());
        $blade->directive('wpempty', $this->ifempty());
        $blade->directive('endwploop', $this->end());
        $blade->directive('wpthe', $this->the());
    }

    protected function start() {
        $self = $this;
        return function ($exp) use ($self) {
            $output = "";
            $prefix = "";

            $exp = $self->cleanExpression($exp);
            $self->startWithExpression = !!($exp);

            if ($self->startWithExpression) {
                $output = "<?php \$__query = new Wp_Query({$exp}); ?> ";
                $prefix = "\$__query->";
            } else {
                $exp = "";
            }

            $self->hasStarted = true;

            return $output .= "<?php if({$prefix}have_posts()): while({$prefix}have_posts()): {$prefix}the_post(); ?>";
        };
    }

    protected function ifempty() {
        $self = $this;

        return function () use ($self) {
            $self->hasElse = true;
            return "<?php endwhile; else: ?>";
        };
    }

    protected function end() {
        $self = $this;

        return function () use ($self) {
            $output = [
                "<?php",
                !$self->hasElse ? "endwhile;" : "",
                "endif;",
                $self->startWithExpression ? "wp_reset_postdata();" : "",
                "?>",
            ];

            $self->reset();

            return implode(" ", array_filter($output));
        };
    }

    protected function the() {
        $self = $this;

        return function ($exp) use ($self) {
            $exp = trim($self->cleanExpression($exp), "\"'");
            if (!$exp) {
                throw new InvalidArgumentException("This directive require at least one argument");
            }

            return "<?php the_$exp(); ?>";
        };
    }

    protected function reset() {
        $this->hasStarted = false;
        $this->hasElse = false;
        $this->startWithExpression = false;
    }

    protected function cleanExpression($exp) {
        return trim(preg_replace("/\(\s+\)/", "()", $exp), "()");
    }
}

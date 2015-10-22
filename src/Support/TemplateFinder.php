<?php
namespace Gladeye\Lucent\Support;

use Illuminate\Contracts\View\Factory as ViewFactory;

class TemplateFinder {

    protected $view;

    public function __construct(ViewFactory $view) {
        $this->view = $view;
    }

    public function find($type, $templates = array()) {
        $type = preg_replace('|[^a-z0-9-]+|', '', $type);

        if (empty($templates)) {
            $templates = array("{$type}");
        }

        $template = $this->filter($templates);
        return $template;
    }

    public function filter($templates) {
        $located = null;

        foreach ((array) $templates as $template) {
            $template = $this->normalise($template);
            if ($this->view->exists($template)) {
                $located = $template;
                break;
            }
        }

        return $located;
    }

    protected function normalise($template) {
        $extensions = array_map(function ($value) {
            return ".{$value}";
        }, array_keys($this->view->getExtensions()));

        return str_replace($extensions, "", $template);
    }
}

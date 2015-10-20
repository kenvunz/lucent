<?php
namespace Gladeye\Lucent;

use Illuminate\Support\Str;

class Template {

    protected $env;

    protected $finder;

    protected $delimeters = ['.', '-'];

    public function __construct(Environment $env,  TemplateFinder $finder) {
        $this->env = $env;
        $this->finder = $finder;
    }

    public function get($type = null) {
        if($type) return $this->{"get".Str::studly("{$type}-template")}();

        $template = false;

        if    ($this->env->is_404()               && $template = $this->get('404')              ) :
        elseif($this->env->is_search()            && $template = $this->get('search')           ) :
        elseif($this->env->is_front_page()        && $template = $this->get('front-page')       ) :
        elseif($this->env->is_home()              && $template = $this->get('home')             ) :
        elseif($this->env->is_post_type_archive() && $template = $this->get('post-type-archive')) :
        elseif($this->env->is_tax()               && $template = $this->get('taxonomy')         ) :
        elseif($this->env->is_attachment()        && $template = $this->get('attachment')       ) :
        elseif($this->env->is_single()            && $template = $this->get('single')           ) :
        elseif($this->env->is_page()              && $template = $this->get('page')             ) :
        elseif($this->env->is_singular()          && $template = $this->get('singular')         ) :
        elseif($this->env->is_category()          && $template = $this->get('category')         ) :
        elseif($this->env->is_tag()               && $template = $this->get('tag')              ) :
        elseif($this->env->is_author()            && $template = $this->get('author')           ) :
        elseif($this->env->is_date()              && $template = $this->get('date')             ) :
        elseif($this->env->is_archive()           && $template = $this->get('archive')          ) :
        elseif($this->env->is_comments_popup()    && $template = $this->get('comments-popup')   ) :
        elseif($this->env->is_paged()             && $template = $this->get('paged')            ) :
        else :
            $template = $this->getIndexTemplate();
        endif;

        return $template;
    }

    protected function getIndexTemplate() {
        return $this->finder->find('index');
    }

    protected function get404Template() {
        return $this->finder->find('404');
    }

    protected function getSearchTemplate() {
        return $this->finder->find('search');
    }

    protected function getFrontPageTemplate() {
        return $this->finder->find('front-page');
    }

    protected function getHomeTemplate() {
        $templates = array('home', 'index');
        return $this->finder->find('home', $templates);
    }

    protected function getPostTypeArchiveTemplate() {
        $type = $this->env->get_query_var('post_type');
        if(is_array($type)) $type = reset($type);

        $obj = $this->env->get_post_type_object($type);
        if(!$obj->has_archive) return;

        return $this->get('archive');
    }

    protected function getArchiveTemplate() {
        $types = array_filter((array) $this->env->get_query_var('post_type'));
        $templates = array();

        if(count($types) === 1) {
            $type = reset($types);
            $templates[] = "archive.{$type}";
            $templates[] = "archive-{$type}";
        }
        $templates[] = 'archive';

        return $this->finder->find('archive', $templates);
    }

    protected function getTaxonomyTemplate() {
        $term = $this->env->get_queried_object();
        $templates = array();

        if(!empty($term->slug)) {
            $taxonomy = $term->taxonomy;
            foreach($this->delimeters as $delimeter) {
                $templates[] = "taxonomy{$delimeter}{$taxonomy}-{$term->slug}";
                $templates[] = "taxonomy{$delimeter}{$taxonomy}";
            }
        }
        $templates[] = 'taxonomy';

        return $this->finder->find('taxonomy', $templates);
    }

    protected function getAttachmentTemplate() {
        $attachment = $this->env->get_queried_object();
        $templates = array();

        if($attachment) {
            if (false !== strpos($attachment->post_mime_type, '/')) {
                list($type, $subtype) = explode('/', $attachment->post_mime_type);
            } else {
                list($type, $subtype) = array($attachment->post_mime_type, '');
            }

            foreach($this->delimeters as $delimeter) {
                $prefix = $delimeter === '.'? 'attachment.' : '';
                if(!empty($subtype)) {
                    $templates[] = "{$prefix}{$type}-{$subtype}";
                    $templates[] = "{$prefix}{$subtype}";
                }
                $templates[] = "{$prefix}{$type}";
            }
        }
        $templates[] = 'attachment';

        return $this->finder->find('attachment', $templates);
    }

    protected function getSingleTemplate() {
        $obj = $this->env->get_queried_object();
        $templates = array();

        if(!empty($obj->post_type)) {
            foreach($this->delimeters as $delimeter)
                $templates[] = "single{$delimeter}{$obj->post_type}";
        }
        $templates[] = "single";

        return $this->finder->find('single', $templates);
    }

    protected function getPageTemplate() {
        $id = $this->env->get_queried_object_id();
        $template = $this->env->get_page_template_slug();
        $pagename = $this->env->get_query_var('pagename');
        $templates = array();

        if(!$pagename && $id) {
            // If a static page is set as the front page, $pagename will not be set.
            // Retrieve it from the queried object
            $page = $this->env->get_queried_object();
            if($page) $pagename = $page->post_name;
        }

        if($template && 0 === $this->env->validate_file($template))
            $templates[] = str_replace('views/', '', $template);

        foreach($this->delimeters as $delimeter) {
            if($pagename) $templates[] = "page{$delimeter}{$pagename}";
            if($id) $templates[] = "page{$delimeter}{$id}";
        }

        $templates[] = 'page';

        return $this->finder->find('page', $templates);
    }

    protected function getSingularTemplate() {
        return $this->finder->find('singular');
    }

    protected function getCategoryTemplate() {
        $category = $this->env->get_queried_object();
        $templates = array();

        if(!empty($category->slug)) {
            foreach($this->delimeters as $delimeter) {
                $templates[] = "category{$delimeter}{$category->slug}";
                $templates[] = "category{$delimeter}{$category->term_id}";
            }
        }
        $templates[] = 'category';

        return $this->finder->find('category', $templates);
    }

    protected function getTagTemplate() {
        $tag = $this->env->get_queried_object();
        $templates = array();

        if(!empty($tag->slug)) {
            foreach($this->delimeters as $delimeter) {
                $templates[] = "tag{$delimeter}{$tag->slug}";
                $templates[] = "tag{$delimeter}{$tag->term_id}";
            }
        }
        $templates[] = 'tag';

        return $this->finder->find('tag', $templates);
    }

    protected function getAuthorTemplate() {
        $author = $this->env->get_queried_object();
        $templates = array();

        if($author instanceof \WP_User) {
            foreach($this->delimeters as $delimeter) {
                $templates[] = "author{$delimeter}{$author->user_nicename}";
                $templates[] = "author{$delimeter}{$author->ID}";
            }
        }
        $templates[] = 'author';

        return $this->finder->find('author', $templates);
    }

    protected function getDateTemplate() {
        return $this->finder->find('date');
    }

    protected function getPagedTemplate() {
        return $this->finder->find('paged');
    }

    protected function getCommentsPopupTemplate() {
        return $this->finder->find('comments-popup');
    }
}

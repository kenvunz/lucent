<?php
namespace Gladeye\Lucent;

use TestCase;
use Mockery as m;

class TemplateTest extends TestCase {

    public function testGetIndexTemplateReturnAsExpected() {
        list($instance, $env, $finder) = $this->getInstance();

        $finder->shouldReceive('find')->once()->with('index')->andReturn('index');

        $this->assertEquals('index', $instance->get('index'));
    }

    public function testGet404TemplateReturnAsExpected() {
        list($instance, $env, $finder) = $this->getInstance();

        $finder->shouldReceive('find')->once()->with('404')->andReturn('404');

        $this->assertEquals('404', $instance->get('404'));
    }

    public function testGetSearchTemplateReturnAsExpected() {
        list($instance, $env, $finder) = $this->getInstance();

        $finder->shouldReceive('find')->once()->with('search')->andReturn('search');

        $this->assertEquals('search', $instance->get('search'));
    }

    public function testGetFrontPageTemplateReturnAsExpected() {
        list($instance, $env, $finder) = $this->getInstance();

        $finder->shouldReceive('find')->once()->with('front-page')->andReturn('front-page');

        $this->assertEquals('front-page', $instance->get('front-page'));
    }

    public function testGetHomePageTemplateReturnAsExpected() {
        list($instance, $env, $finder) = $this->getInstance();

        $finder->shouldReceive('find')->once()->with('home', ['home', 'index'])->andReturn('home');

        $this->assertEquals('home', $instance->get('home'));
    }

    public function testGetPostTypeArchiveTemplateReturnAsExpected() {
        list($instance, $env, $finder) = $this->getInstance();

        $env->shouldReceive('get_query_var')->with('post_type')->twice()->andReturn('post')
            ->shouldReceive('get_post_type_object')->with('post')->once()
            ->andReturn((object) ['has_archive' => true]);

        $finder->shouldReceive('find')->once()->with('archive', [
            'archive.post', 'archive-post', 'archive'
        ])->andReturn('archive.post');

        $this->assertEquals('archive.post', $instance->get('post-type-archive'));
    }

    public function testGetArchiveTemplateReturnAsExpected() {
        list($instance, $env, $finder) = $this->getInstance();

        $env->shouldReceive('get_query_var')->with('post_type')->once()->andReturn('post');
        $finder->shouldReceive('find')->once()->with('archive', [
            'archive.post', 'archive-post', 'archive'
        ])->andReturn('archive-post');

        $this->assertEquals('archive-post', $instance->get('archive'));
    }

    public function testGetTaxonomyTemplateReturnAsExpected() {
        list($instance, $env, $finder) = $this->getInstance();

        $env->shouldReceive('get_queried_object')->once()
            ->andReturn((object)['slug'=>'bar', 'taxonomy'=>'foo']);

        $finder->shouldReceive('find')->once()->with('taxonomy', [
            'taxonomy.foo-bar', 'taxonomy.foo', 'taxonomy-foo-bar', 'taxonomy-foo', 'taxonomy'
        ])->andReturn('taxonomy.foo-bar');

        $this->assertEquals('taxonomy.foo-bar', $instance->get('taxonomy'));
    }

    public function testGetAttachmentTemplateReturnAsExpected() {
        list($instance, $env, $finder) = $this->getInstance();

        $env->shouldReceive('get_queried_object')->once()
            ->andReturn((object)['post_mime_type'=>'text/plain']);

        $finder->shouldReceive('find')->once()->with('attachment', [
            'attachment.text-plain', 'attachment.plain', 'attachment.text',
            'text-plain', 'plain', 'text', 'attachment'
        ])->andReturn('attachment.text-plain');

        $this->assertEquals('attachment.text-plain', $instance->get('attachment'));
    }

    public function testGetSingleTemplateReturnAsExpected() {
        list($instance, $env, $finder) = $this->getInstance();

        $env->shouldReceive('get_queried_object')->once()
            ->andReturn((object)['post_type'=>'post']);

        $finder->shouldReceive('find')->once()->with('single', [
            'single.post', 'single-post', 'single'
        ])->andReturn('single.post');

        $this->assertEquals('single.post', $instance->get('single'));
    }

    public function testGetPageTemplateReturnAsExpected() {
        list($instance, $env, $finder) = $this->getInstance();

        $env->shouldReceive('get_queried_object_id')->once()->andReturn(1)
            ->shouldReceive('get_page_template_slug')->once()->andReturn('views/foo.blade.php')
            ->shouldReceive('get_query_var')->once()->with('pagename')->andReturn('foo')
            ->shouldReceive('validate_file')->once()->andReturn(0);


        $finder->shouldReceive('find')->once()->with('page', [
            'foo.blade.php', 'page.foo', 'page.1', 'page-foo', 'page-1', 'page'
        ])->andReturn('page');

        $this->assertEquals('page', $instance->get('page'));
    }

    public function testGetSingularTemplateReturnAsExpected() {
        list($instance, $env, $finder) = $this->getInstance();

        $finder->shouldReceive('find')->once()->with('singular')->andReturn('singular');

        $this->assertEquals('singular', $instance->get('singular'));
    }

    public function testGetCategoryTemplateReturnAsExpected() {
        list($instance, $env, $finder) = $this->getInstance();

        $env->shouldReceive('get_queried_object')->once()->andReturn((object)[
            "slug"=>"foo",
            "term_id"=>1
        ]);

        $finder->shouldReceive('find')->once()->with('category', [
            'category.foo', 'category.1', 'category-foo', 'category-1', 'category'
        ])->andReturn('category');

        $this->assertEquals('category', $instance->get('category'));
    }

    public function testGetTagTemplateReturnAsExpected() {
        list($instance, $env, $finder) = $this->getInstance();

        $env->shouldReceive('get_queried_object')->once()->andReturn((object)[
            "slug"=>"foo",
            "term_id"=>1
        ]);

        $finder->shouldReceive('find')->once()->with('tag', [
            'tag.foo', 'tag.1', 'tag-foo', 'tag-1', 'tag'
        ])->andReturn('tag');

        $this->assertEquals('tag', $instance->get('tag'));
    }

    public function testGetAuthorTemplateReturnAsExpected() {
        list($instance, $env, $finder) = $this->getInstance();

        $user = m::mock("\WP_User");
        $user->user_nicename = 'foo';
        $user->ID = 1;

        $env->shouldReceive('get_queried_object')->once()->andReturn($user);

        $finder->shouldReceive('find')->once()->with('author', [
            'author.foo', 'author.1', 'author-foo', 'author-1', 'author'
        ])->andReturn('author');

        $this->assertEquals('author', $instance->get('author'));
    }

    public function testGetDateTemplateReturnAsExpected() {
        list($instance, $env, $finder) = $this->getInstance();

        $finder->shouldReceive('find')->once()->with('date')->andReturn('date');

        $this->assertEquals('date', $instance->get('date'));
    }

    public function testGetPagedTemplateReturnAsExpected() {
        list($instance, $env, $finder) = $this->getInstance();

        $finder->shouldReceive('find')->once()->with('paged')->andReturn('paged');

        $this->assertEquals('paged', $instance->get('paged'));
    }

    public function testGetCommentsPopupTemplateReturnAsExpected() {
        list($instance, $env, $finder) = $this->getInstance();

        $finder->shouldReceive('find')->once()->with('comments-popup')->andReturn('comments-popup');

        $this->assertEquals('comments-popup', $instance->get('comments-popup'));
    }

    protected function getInstance() {
        $finder = m::mock('Gladeye\Lucent\TemplateFinder')
            ->shouldIgnoreMissing();
        $env = m::mock(new Environment())
            ->shouldIgnoreMissing();
        $instance = new Template($env, $finder);

        return [$instance, $env, $finder];
    }
}

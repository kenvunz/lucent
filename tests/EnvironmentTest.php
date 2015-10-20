<?php
namespace Gladeye\Lucent;

use TestCase;

class EnvironmentTest extends TestCase
{
    /**
     * @expectedException     \BadFunctionCallException
     */
    public function testExceptionRaisedForUnregisterdFunction()
    {
        $env = new Environment();
        $env->sum();
    }

    public function testAdditionalFunctionCanBeAdded() {
        $env = new Environment();
        $env->addFunction('array_sum');
        $this->assertEquals($env->array_sum([1, 2]), array_sum([1, 2]));
    }
}

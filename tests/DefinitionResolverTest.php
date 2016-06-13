<?php

namespace BlueprintRouter;

use BlueprintRouter\Parser\Definition;

class DefinitionResolverTest extends \PHPUnit_Framework_TestCase
{
    public function testResolveSingleEndpoint()
    {
        $def1 = new Definition(null, 'Foo', null, null);
        $def2 = new Definition($def1, null, null, '/foo');
        $def3 = new Definition($def2, null, 'POST', null);

        $resolver = new DefinitionResolver();
        $eps = $resolver->resolve([$def1, $def2, $def3]);

        $this->assertEquals(
            [new Endpoint(new Definition(null, 'Foo', 'POST', '/foo'))],
            $eps
        );
    }

    public function testResolveMultipleEndpoints()
    {
        $def1 = new Definition(null, 'Foo', null, null);
        $def2 = new Definition($def1, null, null, '/foo');
        $def3 = new Definition($def2, null, 'GET', null);
        $def4 = new Definition($def2, null, 'POST', null);
        $def5 = new Definition($def1, null, 'GET', '/foo/{bar}');

        $resolver = new DefinitionResolver();
        $eps = $resolver->resolve([$def1, $def2, $def3, $def4, $def5]);

        $this->assertEquals(
            [
                new Endpoint(new Definition(null, 'Foo', 'GET', '/foo')),
                new Endpoint(new Definition(null, 'Foo', 'POST', '/foo')),
                new Endpoint(new Definition(null, 'Foo', 'GET', '/foo/{bar}'))
            ],
            $eps
        );
    }

    public function testIgnoreInvalidEndpoints()
    {
        $def1 = new Definition(null, 'Foo', null, null);
        $def2 = new Definition($def1, null, null, '/foo');
        $def3 = new Definition($def2, null, null, '/bar');

        $resolver = new DefinitionResolver();
        $eps = $resolver->resolve([$def1, $def2, $def3]);

        $this->assertEquals(
            [],
            $eps
        );
    }
}

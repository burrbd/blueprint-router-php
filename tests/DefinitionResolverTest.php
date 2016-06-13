<?php

namespace BlueprintRouter;

use BlueprintRouter\Parser\Definition;

class DefinitionResolverTest extends \PHPUnit_Framework_TestCase
{
    public function testResolveSingleEndpoint()
    {
        $def1 = new Definition(null, ['Foo'], null, null);
        $def2 = new Definition($def1, [], null, '/foo');
        $def3 = new Definition($def2, [], 'POST', null);

        $resolver = new DefinitionResolver();
        $eps = $resolver->resolve([$def1, $def2, $def3]);

        $this->assertEquals(
            [new Endpoint(new Definition(null, ['Foo'], 'POST', '/foo'))],
            $eps
        );
    }

    public function testResolveMultipleEndpoints()
    {
        $def1 = new Definition(null, ['Foo'], null, null);
        $def2 = new Definition($def1, [], null, '/foo');
        $def3 = new Definition($def2, [], 'GET', null);
        $def4 = new Definition($def2, [], 'POST', null);
        $def5 = new Definition($def1, [], 'GET', '/foo/{bar}');

        $resolver = new DefinitionResolver();
        $eps = $resolver->resolve([$def1, $def2, $def3, $def4, $def5]);

        $this->assertEquals(
            [
                new Endpoint(new Definition(null, ['Foo'], 'GET', '/foo')),
                new Endpoint(new Definition(null, ['Foo'], 'POST', '/foo')),
                new Endpoint(new Definition(null, ['Foo'], 'GET', '/foo/{bar}'))
            ],
            $eps
        );
    }

    public function testIgnoreInvalidEndpoints()
    {
        $def1 = new Definition(null, ['Foo'], null, null);
        $def2 = new Definition($def1, [], null, '/foo');
        $def3 = new Definition($def2, [], null, '/bar');

        $resolver = new DefinitionResolver();
        $eps = $resolver->resolve([$def1, $def2, $def3]);

        $this->assertEquals(
            [],
            $eps
        );
    }

    public function testMultiItemIdentifierEndpoint()
    {
        $def1 = new Definition(null, ['Foo'], null, null);
        $def2 = new Definition($def1, ['bar'], 'DELETE', '/foo/bar');

        $resolver = new DefinitionResolver();
        $eps = $resolver->resolve([$def1, $def2]);

        $this->assertEquals(
            [new Endpoint(new Definition(null, ['Foo', 'bar'], 'DELETE', '/foo/bar'))],
            $eps
        );
    }
}

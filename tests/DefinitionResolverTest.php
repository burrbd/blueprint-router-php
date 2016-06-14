<?php

namespace BlueprintRouter;

use BlueprintRouter\Parser\Definition;

class DefinitionResolverTest extends \PHPUnit_Framework_TestCase
{
    public function testResolveSingleEndpoint()
    {
        $def1 = new Definition(['Foo']);
        $def2 = new Definition([], null, '/foo', $def1);
        $def3 = new Definition([], 'POST', null, $def2);

        $resolver = new DefinitionResolver();
        $eps = $resolver->resolve([$def1, $def2, $def3]);

        $this->assertEquals(
            [new Endpoint(new Definition(['Foo'], 'POST', '/foo'))],
            $eps
        );
    }

    public function testResolveMultipleEndpoints()
    {
        $def1 = new Definition(['Foo']);
        $def2 = new Definition([], null, '/foo', $def1);
        $def3 = new Definition([], 'GET', null, $def2);
        $def4 = new Definition([], 'POST', null, $def2);
        $def5 = new Definition([], 'GET', '/foo/{bar}', $def1);

        $resolver = new DefinitionResolver();
        $eps = $resolver->resolve([$def1, $def2, $def3, $def4, $def5]);

        $this->assertEquals(
            [
                new Endpoint(new Definition(['Foo'], 'GET', '/foo')),
                new Endpoint(new Definition(['Foo'], 'POST', '/foo')),
                new Endpoint(new Definition(['Foo'], 'GET', '/foo/{bar}'))
            ],
            $eps
        );
    }

    public function testIgnoreInvalidEndpoints()
    {
        $def1 = new Definition(['Foo']);
        $def2 = new Definition([], null, '/foo', $def1);
        $def3 = new Definition([], null, '/bar', $def2);

        $resolver = new DefinitionResolver();
        $eps = $resolver->resolve([$def1, $def2, $def3]);

        $this->assertEquals(
            [],
            $eps
        );
    }

    public function testMultiItemIdentifierEndpoint()
    {
        $def1 = new Definition(['Foo'], null, null);
        $def2 = new Definition(['bar'], 'DELETE', '/foo/bar', $def1);

        $resolver = new DefinitionResolver();
        $eps = $resolver->resolve([$def1, $def2]);

        $this->assertEquals(
            [new Endpoint(new Definition(['Foo', 'bar'], 'DELETE', '/foo/bar'))],
            $eps
        );
    }
}

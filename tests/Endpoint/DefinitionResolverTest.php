<?php

namespace BlueprintRouter\Endpoint;

class DefinitionResolverTest extends \PHPUnit_Framework_TestCase
{
    use DefinitionFactory;

    private function createEndpoint($identifier = [], $method = null, $uriTemplate = null)
    {
        return new Endpoint($this->createDefinition(null, null, $identifier, $method, $uriTemplate));
    }

    public function testResolveSingleEndpoint()
    {
        $def1 = $this->createDefinition(null, null, ['Foo']);
        $def2 = $this->createDefinition($def1, null, [], null, '/foo');
        $def3 = $this->createDefinition($def2, null, [], 'POST');

        $resolver = new DefinitionResolver();
        $eps = $resolver->resolve([$def1, $def2, $def3]);

        $this->assertEquals(
            [$this->createEndpoint(['Foo'], 'POST', '/foo')],
            $eps
        );
    }

    public function testResolveMultipleEndpoints()
    {
        $def1 = $this->createDefinition(null, null, ['Foo']);
        $def2 = $this->createDefinition($def1, null, [], null, '/foo');

        $def3 = $this->createDefinition($def2, null, [], 'GET');
        $def4 = $this->createDefinition($def2, null, [], 'POST');
        $def5 = $this->createDefinition($def1, null, [], 'GET', '/foo/{bar}');

        $resolver = new DefinitionResolver();
        $eps = $resolver->resolve([$def1, $def2, $def3, $def4, $def5]);

        $this->assertEquals(
            [
                $this->createEndpoint(['Foo'], 'GET', '/foo'),
                $this->createEndpoint(['Foo'], 'POST', '/foo'),
                $this->createEndpoint(['Foo'], 'GET', '/foo/{bar}')
            ],
            $eps
        );
    }

    public function testIgnoreInvalidEndpoints()
    {
        $def1 = $this->createDefinition(null, null, ['Foo']);
        $def2 = $this->createDefinition(null, null, [], null, '/foo');
        $def3 = $this->createDefinition($def2, null, [], null, '/bar');

        $resolver = new DefinitionResolver();
        $eps = $resolver->resolve([$def1, $def2, $def3]);

        $this->assertEquals(
            [],
            $eps
        );
    }

    public function testMultiItemIdentifierEndpoint()
    {
        $def1 = $this->createDefinition(null, null, ['Foo'], null, null);
        $def2 = $this->createDefinition($def1, null, ['bar'], 'DELETE', '/foo/bar');

        $resolver = new DefinitionResolver();
        $eps = $resolver->resolve([$def1, $def2]);

        $this->assertEquals(
            [$this->createEndpoint(['Foo', 'bar'], 'DELETE', '/foo/bar')],
            $eps
        );
    }
}

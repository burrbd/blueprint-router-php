<?php

namespace BlueprintRouter;

use BlueprintRouter\Parser\Definition;

class Endpoint
{
    private $identifier;

    private $uriTemplate;

    private $method;

    /**
     * @param Definition $definition
     */
    public function __construct(Definition $definition)
    {
        $this->identifier = $definition->identifier;
        $this->uriTemplate = $definition->uriTemplate;
        $this->method = $definition->method;
    }
}

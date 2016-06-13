<?php

namespace BlueprintRouter;

use BlueprintRouter\Parser\Definition;

class Endpoint
{
    /**
     * @var array
     */
    private $identifier;

    /**
     * @var string
     */
    private $uriTemplate;

    /**
     * @var string
     */
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

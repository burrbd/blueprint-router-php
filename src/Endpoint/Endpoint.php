<?php

namespace BlueprintRouter\Endpoint;

use BlueprintRouter\Parser\DefinitionParser;

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
        $this->identifier = $definition->getIdentifiers();
        $this->uriTemplate = $definition->getUriTemplate();
        $this->method = $definition->getMethod();
    }
}

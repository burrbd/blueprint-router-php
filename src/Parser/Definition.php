<?php

namespace BlueprintRouter\Parser;

class Definition
{
    /**
     * @var Definition
     */
    public $parent;

    /**
     * @var array
     */
    public $identifier;

    /**
     * @var string
     */
    public $method;

    /**
     * @var string
     */
    public $uriTemplate;

    /**
     * @param array      $identifier
     * @param string     $method
     * @param string     $uriTemplate
     * @param Definition $parent
     */
    public function __construct(array $identifier = [], $method = null, $uriTemplate = null, Definition $parent = null)
    {
        $this->identifier = $identifier;
        $this->method = $method;
        $this->uriTemplate = $uriTemplate;
        $this->parent = $parent;
    }

    public function isValidEndpoint()
    {
        return count($this->identifier) > 0
        && null !== $this->method
        && null !== $this->uriTemplate;
    }
}
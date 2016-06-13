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
     * @param Definition $parent
     * @param array      $identifier
     * @param string     $method
     * @param string     $uriTemplate
     */
    public function __construct(Definition $parent = null, array $identifier = [], $method = null, $uriTemplate = null)
    {
        $this->parent = $parent;
        $this->identifier = $identifier;
        $this->method = $method;
        $this->uriTemplate = $uriTemplate;
    }

    public function isValidEndpoint()
    {
        return count($this->identifier) > 0
        && null !== $this->method
        && null !== $this->uriTemplate;
    }
}
<?php

namespace BlueprintRouter;

class Definition
{
    /**
     * @var Definition
     */
    public $parent;

    /**
     * @var string
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
     * @param string     $identifier
     * @param string     $method
     * @param string     $uriTemplate
     */
    public function __construct(Definition $parent = null, $identifier = null, $method = null, $uriTemplate = null)
    {
        $this->parent = $parent;
        $this->identifier = $identifier;
        $this->method = $method;
        $this->uriTemplate = $uriTemplate;
    }
}
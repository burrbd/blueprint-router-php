<?php

namespace BlueprintRouter\Endpoint;

class Definition
{
    /**
     * @var Definition
     */
    private $parent;

    /**
     * @var int
     */
    private $sectionLevel;

    /**
     * @var array
     */
    private $identifiers = [];

    /**
     * @var string
     */
    private $method;

    /**
     * @var string
     */
    private $uriTemplate;

    /**
     * @return Definition
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param Definition $parent
     */
    public function setParent(Definition $parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return int
     */
    public function getSectionLevel()
    {
        return $this->sectionLevel;
    }

    /**
     * @param int $sectionLevel
     */
    public function setSectionLevel($sectionLevel)
    {
        $this->sectionLevel = $sectionLevel;
    }

    /**
     * @return array
     */
    public function getIdentifiers()
    {
        return $this->identifiers;
    }

    /**
     * @param array $identifiers
     */
    public function setIdentifiers(array $identifiers)
    {
        $this->identifiers = $identifiers;
    }

    /**
     * @param string $identifier
     */
    public function appendIdentifier($identifier)
    {
        $this->identifiers[] = $identifier;
    }

    /**
     * @param string $identifier
     */
    public function prependIdentifier($identifier)
    {
        array_unshift($this->identifiers, $identifier);
    }
    
    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @return string
     */
    public function getUriTemplate()
    {
        return $this->uriTemplate;
    }

    /**
     * @param string $uriTemplate
     */
    public function setUriTemplate($uriTemplate)
    {
        $this->uriTemplate = $uriTemplate;
    }

    /**
     * @return bool
     */
    public function isValidEndpoint()
    {
        return count($this->identifiers) > 0
        && null !== $this->method
        && null !== $this->uriTemplate;
    }
}

<?php

namespace BlueprintRouter\Parser\DefinitionParser;

use BlueprintRouter\Endpoint\Definition;
use BlueprintRouter\Parser\DefinitionParser\DefinitionMatcher\SectionDefinitionMatcher;

class SetexHeaderDefinedSection implements DefinitionParser
{
    /**
     * @var SectionDefinitionMatcher
     */
    private $definitionMatcher;

    /**
     * @param SectionDefinitionMatcher $definitionMatcher
     */
    public function __construct(SectionDefinitionMatcher $definitionMatcher)
    {
        $this->definitionMatcher = $definitionMatcher;
    }

    /**
     * @param string   $content
     * @param resource $handle
     *
     * @return Definition|null
     */
    public function parseDefinition($content, $handle)
    {
        // TODO: implement method
    }
}

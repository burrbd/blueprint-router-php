<?php

namespace BlueprintRouter\Parser\DefinitionParser;

use BlueprintRouter\Endpoint\Definition;
use BlueprintRouter\Parser\DefinitionParser\DefinitionMatcher\SectionDefinitionMatcher;

class AtxHeaderDefinedSection implements DefinitionParser
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
        $content = trim($content);

        if ('#' !== $content[0]) {
            return null;
        }

        $level = $this->sectionLevel($content);

        $content = trim(substr($content, $level));

        $definition = $this->definitionMatcher->match($content);

        if (null !== $definition) {
            $definition->setSectionLevel($level);
        }

        return $definition;
    }

    /**
     * @param string   $line
     *
     * @return int
     */
    private function sectionLevel($line)
    {
        $i = 0;
        while ($line[$i] === '#') {
            $i++;
        }

        return $i;
    }
}

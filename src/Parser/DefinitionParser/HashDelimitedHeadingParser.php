<?php

namespace BlueprintRouter\Parser\DefinitionParser;

use BlueprintRouter\Endpoint\Definition;
use BlueprintRouter\Parser\DefinitionParser\Tokenizer\DefinitionTokenizer;

class HashDelimitedHeadingParser implements DefinitionParser
{
    /**
     * @var DefinitionTokenizer
     */
    private $definitionTokenizer;

    /**
     * @param DefinitionTokenizer $definitionTokenizer
     */
    public function __construct(DefinitionTokenizer $definitionTokenizer)
    {
        $this->definitionTokenizer = $definitionTokenizer;
    }

    /**
     * @param string   $line
     *
     * @return int
     */
    private function sectionLevel($line)
    {
        $line = trim($line);

        $i = 0;
        while ($line[$i] === '#') {
            $i++;
        }

        return $i;
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

        $definition = $this->definitionTokenizer->tokenizeDefinition($content);

        if (null !== $definition) {
            $definition->setSectionLevel($level);
        }

        return $definition;
    }
}

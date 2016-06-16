<?php

namespace BlueprintRouter\Parser\DefinitionParser;

interface DefinitionParser
{
    /**
     * @param string   $content
     * @param resource $handle
     *
     * @return \BlueprintRouter\Endpoint\Definition|null
     */
    public function parseDefinition($content, $handle);
}

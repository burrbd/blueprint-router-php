<?php

namespace BlueprintRouter\Parser\SectionParser;

interface SectionParser
{
    /**
     * @param string   $content
     * @param resource $handle
     *
     * @return \BlueprintRouter\Endpoint\Definition|null
     */
    public function parseDefinition($content, $handle);
}

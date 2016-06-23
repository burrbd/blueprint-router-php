<?php

namespace BlueprintRouter\Parser\SectionParser\DefinitionMatcher;

use BlueprintRouter\Endpoint\Definition;

interface SectionDefinitionMatcher
{
    /**
     * @param $content
     *
     * @return Definition|null
     */
    public function match($content);
}

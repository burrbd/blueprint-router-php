<?php

namespace BlueprintRouter\Parser\DefinitionParser\DefinitionMatcher;

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

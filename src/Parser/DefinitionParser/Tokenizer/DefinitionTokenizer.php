<?php

namespace BlueprintRouter\Parser\DefinitionParser\Tokenizer;

use BlueprintRouter\Endpoint\Definition;

interface DefinitionTokenizer
{
    /**
     * @param $content
     *
     * @return Definition|null
     */
    public function tokenizeDefinition($content);
}

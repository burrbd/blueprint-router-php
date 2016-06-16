<?php

namespace BlueprintRouter\Parser\DefinitionParser\Tokenizer;

use BlueprintRouter\Endpoint\Definition;

class BasicDefinitionTokenizer implements DefinitionTokenizer
{
    /**
     * @param string $content
     *
     * @return Definition|null
     */
    public function tokenizeDefinition($content)
    {
        $definition = new Definition();

        preg_match('/^Group\s+([^\[\]\(\)]+)$/', $content, $matches);
        if (count($matches) === 2) {
            $definition->appendIdentifier(trim($matches[1]));

            return $definition;
        }

        preg_match('/(^\/\S+)$/', $content, $matches);
        if (count($matches) === 2) {
            $definition->setUriTemplate($matches[0]);

            return $definition;
        }

        // # <identifier> [<URI template>]
        preg_match('/^([^\[\]\(\)]+)\[(\/\S+)\]$/', $content, $matches);
        if (count($matches) === 3) {
            $definition->setUriTemplate($matches[2]);
            $definition->appendIdentifier(trim($matches[1]));

            return $definition;
        }

        // ## <HTTP request method>
        preg_match('/^(GET|PUT|POST|PATCH|DELETE)$/', $content, $matches);
        if (count($matches) === 2) {
            $definition->setMethod($matches[1]);

            return $definition;
        }

        // ## <identifier> [<HTTP request method>]
        preg_match('/^([^\[\]\(\)]+)\[(GET|PUT|POST|PATCH|DELETE)\]$/', $content, $matches);
        if (count($matches) === 3) {
            $definition->appendIdentifier(trim($matches[1]));
            $definition->setMethod($matches[2]);

            return $definition;
        }

        preg_match('/^([^\[\]\(\)]+)\[(GET|PUT|POST|PATCH|DELETE)\s+(\/\S+)\]$/', $content, $matches);
        if (count($matches) === 4) {
            $definition->appendIdentifier(trim($matches[1]));
            $definition->setMethod($matches[2]);
            $definition->setUriTemplate($matches[3]);

            return $definition;
        }

        preg_match('/^(GET|PUT|POST|PATCH|DELETE)\s+(\/\S+)$/', $content, $matches);
        if (count($matches) === 3) {
            $definition->setMethod($matches[1]);
            $definition->setUriTemplate($matches[2]);

            return $definition;
        }

        return null;
    }
}

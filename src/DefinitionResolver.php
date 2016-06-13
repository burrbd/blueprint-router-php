<?php

namespace BlueprintRouter;

use BlueprintRouter\Parser\Definition;

class DefinitionResolver
{
    /**
     * @param Definition[] $definitions
     *
     * @return Endpoint[]
     */
    public function resolve(array $definitions)
    {
        $endpoints = [];

        foreach ($definitions as $definition) {

            $endpoint = clone $definition;

            $this->recurseAncestors($endpoint, $endpoint->parent);

            if ($endpoint->isValidEndpoint()) {
                $endpoints[] = new Endpoint($endpoint);
            }
        }

        return $endpoints;
    }

    /**
     * @param Definition $endpoint
     * @param Definition $ancestor
     *
     * @return null
     */
    private function recurseAncestors(Definition $endpoint, Definition $ancestor = null)
    {
        if (null === $ancestor) {
            return;
        }

        if (0 !== count($ancestor->identifier)) {
            $ancestorIdentifiersReversed = array_reverse($ancestor->identifier);
            foreach($ancestorIdentifiersReversed as $identifierItem) {
                array_unshift($endpoint->identifier, $identifierItem);
            }
        }

        if (null === $endpoint->method) {
            $endpoint->method = $ancestor->method;
        }

        if (null === $endpoint->uriTemplate) {
            $endpoint->uriTemplate = $ancestor->uriTemplate;
        }

        $this->recurseAncestors($endpoint, $ancestor->parent);

        return;
    }
}

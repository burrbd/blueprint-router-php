<?php

namespace BlueprintRouter\Endpoint;

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

            $this->recurseAncestors($endpoint, $endpoint->getParent());

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

        if (0 !== count($ancestor->getIdentifiers())) {
            $ancestorIdentifiersReversed = array_reverse($ancestor->getIdentifiers());
            foreach($ancestorIdentifiersReversed as $identifierItem) {
                $endpoint->prependIdentifier($identifierItem);
            }
        }

        if (null === $endpoint->getMethod()) {
            $endpoint->setMethod($ancestor->getMethod());
        }

        if (null === $endpoint->getUriTemplate()) {
            $endpoint->setUriTemplate($ancestor->getUriTemplate());
        }

        $this->recurseAncestors($endpoint, $ancestor->getParent());
    }
}

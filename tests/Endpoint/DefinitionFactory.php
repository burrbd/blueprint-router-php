<?php

namespace BlueprintRouter\Endpoint;

trait DefinitionFactory
{
    private function createDefinition($parent = null, $sectionLevel = null, $identifiers = [], $method = null, $uriTemplate = null)
    {
        $d = new Definition();
        if (null !== $parent) {
            $d->setParent($parent);
        }
        $d->setSectionLevel($sectionLevel);
        $d->setIdentifiers($identifiers);
        $d->setMethod($method);
        $d->setUriTemplate($uriTemplate);

        return $d;
    }
}

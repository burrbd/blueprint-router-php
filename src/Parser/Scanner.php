<?php

namespace BlueprintRouter\Parser;

use BlueprintRouter\Endpoint\Definition;
use BlueprintRouter\Parser\SectionParser\SectionParser;

class Scanner
{
    /**
     * @var SectionParser[]
     */
    private $definitionParsers;

    /**
     * @param SectionParser $definitionParser
     */
    public function addDefinitionParser(SectionParser $definitionParser)
    {
        $this->definitionParsers[] = $definitionParser;
    }

    /**
     * @param resource $handle
     *
     * @return null
     */
    public function scan($handle)
    {
        if (!is_resource($handle)) {
            throw new \InvalidArgumentException('Invalid argument');
        }

        $definitions = [];
        
        while (($line = fgets($handle)) !== false) {

            $definition = $this->parseDefinition($line, $handle);

            if (null !== $definition) {

                $parent = $this->findParent($definition, $definitions);

                if (null !== $parent) {
                    $definition->setParent($parent);
                }

                $definitions[] = $definition;
            }
        }

        fclose($handle);
        
        return $definitions;
    }

    /**
     * @param string $definitionText
     * @param        $handle
     *
     * @return Definition|null
     */
    private function parseDefinition($definitionText, $handle)
    {
        foreach ($this->definitionParsers as $parser) {

            $definition = $parser->parseDefinition($definitionText, $handle);

            if ($definition) {
                return $definition;
            }
        }

        return null;
    }

    /**
     * @param Definition   $definition
     * @param Definition[] $definitions
     *
     * @return Definition|null
     */
    private function findParent(Definition $definition, array $definitions)
    {
        for ($i = count($definitions) - 1; $i >= 0; $i--) {
            if ($definition->getSectionLevel() > $definitions[$i]->getSectionLevel()) {
                return $definitions[$i];
            }
        }

        return null;
    }
}

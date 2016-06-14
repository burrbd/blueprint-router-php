<?php

namespace BlueprintRouter\Parser;

class Scanner
{
    /**
     * @var HeadingParser
     */
    private $headingParser;

    /**
     * @param HeadingParser $headingParser
     */
    public function __construct(HeadingParser $headingParser)
    {
        $this->headingParser = $headingParser;
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
            if ($this->headingParser->isHeading($line, $handle)) {

                $definition = $this->parseDefinition(
                    $this->headingParser->headingContents($line)
                );

                if (null !== $definition) {
                    $level = $this->headingParser->headingLevel($line);
                    $definition->parent = $this->findParent($level, $definitions);
                    $definitions[] = [$level, $definition];
                }

                // TODO: add parameters to definition
            }
        }

        fclose($handle);

        return array_map(function ($n) {return $n[1];}, $definitions);
    }

    /**
     * @param string          $text
     *
     * @return Definition|null
     */
    private function parseDefinition($text)
    {
        // # Group <identifier>
        preg_match('/^Group\s+([^\[\]\(\)]+)$/', $text, $matches);
        if (count($matches) === 2) {
            $identifier = trim($matches[1]);

            return new Definition([$identifier]);
        }

        // # <URI template>
        preg_match('/(^\/\S+)$/', $text, $matches);
        if (count($matches) === 2) {
            $uriTemplate = $matches[0];

            return new Definition([], null, $uriTemplate);
        }

        // # <HTTP request method> <URI template>
        preg_match('/^(GET|PUT|POST|PATCH|DELETE)\s+(\/\S+)$/', $text, $matches);
        if (count($matches) === 3) {
            $method = $matches[1];
            $uriTemplate = $matches[2];

            return new Definition([], $method, $uriTemplate);
        }

        // # <identifier> [<URI template>]
        preg_match('/^([^\[\]\(\)]+)\[(\/\S+)\]$/', $text, $matches);
        if (count($matches) === 3) {
            $uriTemplate = $matches[2];
            $identifier = trim($matches[1]);

            return new Definition([$identifier], null, $uriTemplate);
        }

        // # <identifier> [<HTTP request method> <URI template>]
        preg_match('/^([^\[\]\(\)]+)\[(GET|PUT|POST|PATCH|DELETE)\s+(\/\S+)\]$/', $text, $matches);
        if (count($matches) === 4) {
            $identifier = trim($matches[1]);
            $method = $matches[2];
            $uriTemplate = $matches[3];

            return new Definition([$identifier], $method, $uriTemplate);
        }

        // ## <HTTP request method>
        preg_match('/^(GET|PUT|POST|PATCH|DELETE)$/', $text, $matches);
        if (count($matches) === 2) {
            $method = $matches[1];

            return new Definition([], $method);
        }

        // ## <identifier> [<HTTP request method>]
        preg_match('/^([^\[\]\(\)]+)\[(GET|PUT|POST|PATCH|DELETE)\]$/', $text, $matches);
        if (count($matches) === 3) {
            $identifier = trim($matches[1]);
            $method = $matches[2];

            return new Definition([$identifier], $method);
        }

        return null;
    }

    /**
     * @param int          $level
     * @param Definition[] $definitions
     *
     * @return Definition|null
     */
    private function findParent($level, array $definitions)
    {
        for ($i = count($definitions) - 1; $i >= 0; $i--) {
            if ($level > $definitions[$i][0]) {
                return $definitions[$i][1];
            }
        }

        return null;
    }
}

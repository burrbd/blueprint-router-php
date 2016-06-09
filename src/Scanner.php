<?php

namespace BlueprintRouter;

class Scanner
{
    /**
     * @var HeadingParser
     */
    private $headingParser;

    /**
     * @var []Definition
     */
    private $scannedDefinitions;

    public function __construct(HeadingParser $headingParser)
    {
        $this->headingParser = $headingParser;
    }

    /**
     * @param resource $fileHandle
     *
     * @return null
     */
    public function scan($fileHandle)
    {
        if (!is_resource($fileHandle)) {
            throw new \InvalidArgumentException('Invalid argument');
        }

        $definitions = [];

        if ($fileHandle) {
            while (($line = fgets($fileHandle)) !== false) {
                if ($this->headingParser->isHeading($line)) {

                    $level = $this->headingParser->headingLevel($line);

                    $definition = $this->scanDefinition(
                        $this->resolveParent($level),
                        $this->headingParser->headingContents($line)
                    );

                    if (null !== $definition) {
                        $definitions[] = $definition;
                        $this->scannedDefinitions[] = [$level, $definition];
                    }
                }
            }

            fclose($fileHandle);
        }

        return $definitions;
    }

    /**
     * @param Definition|null $parent
     * @param string          $text
     *
     * @return Definition|null
     */
    private function scanDefinition(Definition $parent = null, $text)
    {
        // Group <identifier>
        preg_match('/^Group\s+([^\[\]\(\)]+)$/', $text, $matches);
        if (count($matches) === 2) {
            $identifier = $matches[1];

            return new Definition($parent, $identifier);
        }

        // <URI template>
        preg_match('/(^\/\S+)$/', $text, $matches);
        if (count($matches) === 2) {
            $uriTemplate = $matches[0];

            return new Definition($parent, null, null, $uriTemplate);
        }

        // <HTTP request method> <URI template>
        preg_match('/^(GET|PUT|POST|PATCH|DELETE)\s+(\/\S+)$/', $text, $matches);
        if (count($matches) === 3) {
            $method = $matches[1];
            $uriTemplate = $matches[2];

            return new Definition($parent, null, $method, $uriTemplate);
        }

        // <identifier> [<URI template>]
        preg_match('/^([^\[\]\(\)]+)\[(\/\S+)\]$/', $text, $matches);
        if (count($matches) === 3) {
            $uriTemplate = $matches[2];
            $identifier = trim($matches[1]);

            return new Definition($parent, $identifier, null, $uriTemplate);
        }

        // <identifier> [<HTTP request method> <URI template>]
        preg_match('/^([^\[\]\(\)]+)\[(GET|PUT|POST|PATCH|DELETE)\s+(\/\S+)\]$/', $text, $matches);
        if (count($matches) === 4) {
            $identifier = trim($matches[1]);
            $method = $matches[2];
            $uriTemplate = $matches[3];

            return new Definition($parent, $identifier, $method, $uriTemplate);
        }

        return null;
    }

    /**
     * @param int $level
     *
     * @return Definition|null
     */
    private function resolveParent($level)
    {
        for ($i = count($this->scannedDefinitions); $i >= 0; $i--) {
            if ($level > $this->scannedDefinitions[$i][0]) {
                return $this->scannedDefinitions[$i];
            }
        }

        return null;
    }
}
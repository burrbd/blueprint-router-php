<?php

namespace BlueprintRouter\Parser;

interface HeadingParser
{
    /**
     * @param string   $line
     * @param resource $handle
     *
     * @return bool
     */
    public function isHeading($line, $handle);

    /**
     * @param string $line
     *
     * @return int
     */
    public function headingLevel($line);

    /**
     * @param string $line
     *
     * @return string
     */
    public function headingContents($line);
}

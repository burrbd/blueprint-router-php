<?php

namespace BlueprintRouter\Parser;

class HashDelimitedHeadingParser implements HeadingParser
{
    /**
     * @param string   $line
     * @param resource $handle
     *
     * @return bool
     */
    public function isHeading($line, $handle)
    {
        $line = trim($line);

        return '#' === $line[0];
    }

    /**
     * @param string $line
     *
     * @return int
     */
    public function headingLevel($line)
    {
        $line = trim($line);

        $i = 0;
        while ($line[$i] === '#') {
            $i++;
        }

        return $i;
    }

    /**
     * @param string $line
     *
     * @return string
     */
    public function headingContents($line)
    {
        $line = trim($line);
        $level = $this->headingLevel($line);

        return trim(substr($line, $level));
    }
}

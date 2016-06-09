<?php

namespace BlueprintRouter;

interface HeadingParser
{
    /**
     * @param string $line
     *
     * @return bool
     */
    public function isHeading($line);

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

<?php

namespace SDM\Import;

class Import implements ImportInterface
{
    /**
     * Importer.
     *
     * @param string $filePath  The path to the csv file
     * @param string $delimiter CSV delimter
     */
    public function __construct(string $filePath, string $delimiter = ',')
    {
    }
}

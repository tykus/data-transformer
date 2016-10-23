<?php
namespace Tykus\DataTransformer\Importers;

use League\Csv\Reader;

class CsvImporter extends Importer
{
    /**
     * Extracts the CSV data from the file
     *
     * @return Array
     */
    protected function readFile()
    {
        $reader = Reader::createFromPath($this->filename);
        return $reader->fetchAssoc();
    }

}

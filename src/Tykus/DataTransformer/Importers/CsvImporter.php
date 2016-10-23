<?php
namespace Tykus\DataTransformer\Importers;

use League\Csv\Reader;

class CsvImporter extends Importer
{
    /**
     * Extracts the CSV data from the file
     *
     * @return Illuminate\Support\Collection
     */
    public function readFile()
    {
        $reader = Reader::createFromPath($this->filename);
        return $reader->fetchAssoc();
    }

}

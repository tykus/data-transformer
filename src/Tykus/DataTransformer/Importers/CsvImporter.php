<?php
namespace Tykus\DataTransformer\Importers;

use League\Csv\Reader;
use Illuminate\Support\Collection;

class CsvImporter extends Importer
{
    /**
     * Extracts the CSV data from the file
     *
     * @return Illuminate\Support\Collection
     */
    public function get()
    {
        $reader = Reader::createFromPath($this->filename);
        return Collection::make($reader->fetchAssoc());
    }

}

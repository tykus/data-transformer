<?php
namespace Tykus\DataTransformer\Importers;

use Illuminate\Support\Collection;

abstract class Importer
{
    /**
     * The provided filename to be read and imported
     *
     * @var string
     */
    protected $filename;

    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    /**
     * Get the data from the provided file.
     *
     * @return Illuminate\Support\Collection
     */
    public function get()
    {
        return Collection::make($this->readFile());
    }

    /**
     * Read the file
     * @return Array
     */
    abstract protected function readFile();

}

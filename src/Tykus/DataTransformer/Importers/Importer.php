<?php
namespace Tykus\DataTransformer\Importers;

use Illuminate\Support\Collection;

abstract class Importer
{
    protected $filename;

    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    public function get()
    {
        return Collection::make($this->readFile());
    }

    abstract public function readFile();

}

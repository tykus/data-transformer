<?php
namespace Tykus\DataTransformer\Importers;

abstract class Importer
{
    protected $filename;

    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    abstract public function get();

}

<?php
namespace Tykus\DataTransformer;

use Tykus\DataTransformer\FormatIdentifier;
use Tykus\DataTransformer\Exceptions\FileNotFoundException;
use Tykus\DataTransformer\Exceptions\UnsupportedFormatException;

class Transformer
{
    protected $filename;
    protected $formatIdentifier;
    protected $importer;
    protected $exporter;
    protected $data;

    public function __construct($filename, FormatIdentifier $formatIdentifier)
    {
        $this->filename = $this->validateFileExists($filename);
        $this->formatIdentifier = $formatIdentifier;
        $this->data = $this->getData();
    }

    private function validateFileExists($filename)
    {
        if (! file_exists($filename))
        {
            throw new FileNotFoundException("The provided filename does not exist.");
        }

        return $filename;
    }

    public function getImporter()
    {
        return $this->importer;
    }

    public function getExporter()
    {
        return $this->exporter;
    }

    public function __call($method, $args)
    {
        if (strpos($method, 'to') == 0)
        {
            $format = substr($method, 2);
            return $this->exporter = $this->fetchExporter($format);
        }

        throw new \BadMethodCallException("{get_class($this)} does not respond to {$method}");
    }

    private function fetchExporter($format)
    {
        $exporter = $format . 'Exporter';

        $reflector = new \ReflectionClass("Tykus\\DataTransformer\\Exporters\\{$exporter}");

        return $reflector->newInstance();
    }

    private function fetchImporter()
    {
        $format = $this->formatIdentifier->identify($this->filename);

        $class = ucwords($format) . 'Importer';
        $reflector = new \ReflectionClass("Tykus\\DataTransformer\\Importers\\{$class}");

        return $reflector->newInstanceArgs([$this->filename]);
    }

    private function getData()
    {
        if (! isset($this->importer))
        {
            $this->importer = $this->fetchImporter();
        }

        return $this->importer->get();
    }

}

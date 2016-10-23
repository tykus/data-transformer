<?php
namespace Tykus\DataTransformer;

use Tykus\DataTransformer\Exceptions\FileNotFoundException;
use Tykus\DataTransformer\Exceptions\UnsupportedFormatException;
use Tykus\DataTransformer\FormatIdentifier;

class Transformer
{
    protected $filename;
    protected $formatIdentifier;
    protected $importer;
    protected $exporter;

    public function __construct($filename, FormatIdentifier $formatIdentifier)
    {
        $this->filename = $this->validateFileExists($filename);
        $this->formatIdentifier = $formatIdentifier;
        $this->fetchImporter();
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
        if (! isset($this->importer))
        {
            return $this->fetchImporter();
        }

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

        return $reflector->newInstance([$this->filename]);
    }

}

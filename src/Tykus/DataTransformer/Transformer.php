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
        $this->data = $this->fetchData();
    }

    /**
     * Returns the current Importer instance
     *
     * @return Tykus\DataTransformer\Importers\Importer
     */
    public function getImporter()
    {
        return $this->importer;
    }

    /**
     * Returns the current Exporter instance
     *
     * @return Tykus\DataTransformer\Exporters\Exporter
     */
    public function getExporter()
    {
        return $this->exporter;
    }

    /**
     * Transform the data according to the rules provided
     * @param  callable $callback
     * @return Illuminate\Support\Collection
     */
    public function transform(callable $func)
    {
        return $this->data->map(function($row) use ($func) {
            return $func($row);
        });
    }

    /**
     * Handles calls to undefined methods - for dynamic exports
     *
     * @param  string $method
     * @param  array $args
     *
     * @return mixed
     */
    public function __call($method, $args)
    {
        if (strpos($method, 'to') == 0)
        {
            $format = substr($method, 2);
            return $this->exporter = $this->createExporter($format);
        }

        throw new \BadMethodCallException("{get_class($this)} does not respond to {$method}");
    }

    /**
     * Validate that the provided filename exists
     *
     * @param  string $filename
     * @return string
     */
    private function validateFileExists($filename)
    {
        if (! file_exists($filename))
        {
            throw new FileNotFoundException("The provided filename does not exist.");
        }

        return $filename;
    }

    /**
     * Determine the name of the exporter class to be used and create it
     *
     * @param string $format
     *
     * @return Tykus\DataTransformer\Exporters\Exporter
     */
    private function createExporter($format)
    {
        $exporter = $format . 'Exporter';

        $reflector = new \ReflectionClass("Tykus\\DataTransformer\\Exporters\\{$exporter}");

        return $reflector->newInstanceArgs([$this->data]);
    }


    /**
     * Determine the name of the importer class to be used and create it
     *
     * @return Tykus\DataTransformer\Importers\Importer
     */
    private function createImporter()
    {
        $format = $this->formatIdentifier->identify($this->filename);

        $class = ucwords($format) . 'Importer';
        $reflector = new \ReflectionClass("Tykus\\DataTransformer\\Importers\\{$class}");

        return $reflector->newInstanceArgs([$this->filename]);
    }

    /**
     * Get the data from the importer
     *
     * @return Illuminate\Support\Collection
     */
    private function fetchData()
    {
        if (! isset($this->importer)) {
            $this->importer = $this->createImporter();
        }
        return $this->importer->get();
    }

}

<?php
namespace Tykus\DataTransformer\Exporters;

abstract class Exporter
{
    protected $data;
    protected $json;

    public function __construct($data)
    {
        $this->data = $data;
        $this->json = $this->write();
    }

    abstract public function write();

    /**
     * This object outputs it's JSON data
     *
     * @return string
     */
    public function __toString()
    {
        return $this->json;
    }
}

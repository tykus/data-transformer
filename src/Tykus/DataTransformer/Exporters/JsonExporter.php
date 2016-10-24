<?php
namespace Tykus\DataTransformer\Exporters;

class JsonExporter extends Exporter
{
    /**
     * Writes the data as JSON
     *
     * @return string
     */
    public function write()
    {
        return $this->data->toJson();
    }
}

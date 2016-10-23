<?php
namespace Tykus\DataTransformer\Exporters;
use SebastianBergmann\Exporter\Exporter;

abstract class Exporter
{
    abstract public function write($filename);
}

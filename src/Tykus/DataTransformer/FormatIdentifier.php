<?php
namespace Tykus\DataTransformer ;

use Symfony\Component\HttpFoundation\File\File;
use Tykus\DataTransformer\Exceptions\UnsupportedFormatException;

class FormatIdentifier
{
    protected $filename;
    protected $file;
    protected $formats = [
        'csv'
    ];


    public function __construct($filename)
    {
        $this->filename = $filename;
        $this->file = $this->setFile($filename);
    }

    public function identify()
    {
        $format = $this->file->getExtension();

        if (! in_array($format, $this->formats))
        {
            throw new UnsupportedFormatException("The provided file is not supported.");
        }

        return $format;
    }

    private function setFile($filename)
    {
        return new File($filename);
    }
}

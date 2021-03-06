<?php
namespace Tykus\DataTransformer\Exceptions;

class FileNotFoundException extends \Exception{

  protected $message;

  public function __construct($message, $code = 0, \Exception $previous = null)
  {
      parent::__construct($message, $code, $previous);
  }
}

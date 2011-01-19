<?php

class TextMazeParser extends StringMazeParser
{
  private $filename = null;

  public function __construct($filename, array $symbol_map = null)
  {
    parent::__construct(null, $symbol_map);
    $this->filename = $filename;
  }

  public function getString()
  {
    if (!file_exists($filename))
    {
      throw new Exception(sprintf('File "%s" doesn\'t exists!', $filename));
    }
    return file_get_contents($filename, FILE_TEXT);
  }
}

?>

<?php

class TextMazeRenderer implements iMazeRenderer
{
  protected
    $maze = null,
    $path = null,
    $time = null;

  public function __construct(Maze $maze, MazePath $path, Timer $time = null)
  {
    $this->maze = $maze;
    $this->path = $path;
    $this->time = $time;
  }

  public function ascii($step = null)
  {
    $ascii = array();
    $maze = $this->maze;
    // draw whole maze
    foreach ($maze->nodes() as $index => $node)
    {
      $point = $maze->point($index);
      $ascii[$point->row()][$point->col()] = (string) $node;
    }
    // mark cells in path (excluding start & end positions)
    $n = 0;
    foreach ($this->path->getPoints(true) as $i)
    {
      if (!empty($step) && ++$n > $step)
      {
        break;
      }
      $point = $maze->point($i);
      $ascii[$point->row()][$point->col()] = 'X';
    }
    return $ascii;
  }

  public function output($step = null)
  {
    $output = $this->header() . "\n\n";
    foreach ($this->ascii($step) as $row)
    {
      $output .= join('', $row) . "\n";
    }
    return $output;
  }

  protected function header()
  {
    return sprintf("Steps: %s | Time: %s | Solvable: %s",
      $this->path->steps(), $this->timeString(), $this->path->exists() 
        ? 'yes :)' 
        : 'no :('
    );
  }

  protected function timeString()
  {
    return is_null($this->time) 
      ? '-' 
      : sprintf('%.3fs', $this->time->elapsed());
  }
}

?>

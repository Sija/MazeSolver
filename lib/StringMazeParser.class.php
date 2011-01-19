<?php

class StringMazeParser implements iMazeParser
{
  private $string = null;
  private $symbol_map = array(
    'space'  => ' ',
    'wall'   => '*',
    'start'  => 'A',
    'end'    => 'B',
    'doors'  => array(
      '>' => MazePoint::POS_LEFT,
      '<' => MazePoint::POS_RIGHT,
      'V' => MazePoint::POS_UP,
      '^' => MazePoint::POS_DOWN
    )
  );

  public function __construct($string, array $symbol_map = null)
  {
    if (!empty($symbol_map))
    {
      $this->symbol_map = array_merge($this->symbol_map, $symbol_map);
    }
    $this->string = $string;
  }

  public function getString()
  {
    return $this->string;
  }

  public function parse()
  {
    // remove windows carret return characters
    $lines = str_replace("\r", '', $this->getString());
    $lines = explode("\n", $lines);

    foreach ($lines as &$line)
    {
      // string to array conversion
      $line = preg_split('//', trim($line), -1, PREG_SPLIT_NO_EMPTY);
    }

    $rows = count($lines);
    $cols = count($lines[0]);
    $maze = new Maze($cols);

    for ($row = 0, $idx = 0; $row < $rows; $row++)
    {
      for ($col = 0; $col < $cols; $col++, $idx++)
      {
        $cell = (isset($lines[$row][$col]) ? $lines[$row][$col] : null);
        // default, anonymous node element
        $node = new MazeNode($cell);

        // iterate through symbol map to determine node class
        foreach ($this->symbol_map as $name => $symbol)
        {
          if ('doors' == $name)
          {
            if (is_array($symbol) && isset($symbol[$cell]))
            {
              $node = new MazeNodeDoors($cell, $symbol[$cell]);
            }
            break;
          }
          else if ($cell == $symbol)
          {
            $node_name = sprintf('MazeNode%s', ucfirst($name));
            // instantiate node object if matching class exists
            if (class_exists($node_name))
            {
              $node = new $node_name($symbol);
            }
            break;
          }
        }
        $maze->addNode($idx, $node);

        // if node happened to be a wall, skip it
        if ($node instanceof MazeNodeWall)
          continue;

        // add neighbor on the bottom if exists
        if (!empty($lines[$row + 1][$col]))
          $maze->addEdge($idx, $idx + $cols);

        // add neighbor on the right if exists
        if (!empty($lines[$row][$col + 1]))
          $maze->addEdge($idx, $idx + 1);

        // add neighbor on the top if exists
        if (!empty($lines[$row - 1][$col]))
          $maze->addEdge($idx, $idx - $cols);

        // add neighbor on the left if exists
        if (!empty($lines[$row][$col - 1]))
          $maze->addEdge($idx, $idx - 1);
      }
    }
    return $maze;
  }
}

?>
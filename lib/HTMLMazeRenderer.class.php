<?php

class HTMLMazeRenderer extends TextMazeRenderer
{
  protected function header()
  {
    return preg_replace('/(.*?): ([^|]+)/', 
      '\\1: <strong>\\2</strong>', 
      parent::header()
    );
  }

  /**
   * Returns <div> with rendered Maze
   *
   * @param  integer  No. of step to show
   * @param  boolean  Should show steps below the maze
   *
   * @return string
   */
  public function output($step = null, $show_steps = false)
  {
    $maze  = $this->maze;
    $path  = $this->path;
    $ascii = $this->ascii($step);
    $rows  = count($ascii);
    $cols  = count($ascii[0]);
    $output = 
      '<div class="maze">' .
        '<div class="header">' . $this->header() . '</div>' .
        '<div class="ascii">';

    for ($row = 0, $idx = 0; $row < $rows; $row++)
    {
      $output .= '<div class="row">';
      for ($col = 0; $col < $cols; $col++, $idx++)
      {
        $symbol   = trim($ascii[$row][$col]);
        $node     = $maze->node($idx);
        $in_path  = $path->includes($maze->point($idx), true);
        $classes  = array(get_class($node));
        if ($in_path)
        {
          $classes[] = 'in_path';
        }
        $output .= sprintf(
          '<div id="maze-cell-%s" class="cell row-%d col-%d %s">%s</div>', // html template
          $idx, $row, $col, join(' ', $classes), // id, classes
          ($symbol ? $symbol : '&nbsp;') // filling
        );
      }
      $output .= '</div>'; // .row
      $output .= '<div class="clear"></div>'; // .clear
    }
    $output .= '</div>'; // .ascii
    if ($show_steps)
    {
      $output .= sprintf('<pre>Steps: %s</pre>', var_export($path->getPoints(), true));
    }
    $output .= '</div>'; // .maze
    return $output;
  }
}

?>
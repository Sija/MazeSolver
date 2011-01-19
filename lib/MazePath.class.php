<?php

class MazePath implements Countable
{
  private $points = array();

  /**
   * @return integer  Number of steps (excluding end point)
   */
  public function steps()
  {
    return max(0, count($this) - 1);
  }

  /**
   * @return integer  Number of steps (including start and end point)
   */
  public function count()
  {
    return count($this->points);
  }

  /**
   * @return boolean  True if path exists (thus maze is solvable)
   */
  public function exists()
  {
    return count($this) > 0;
  }

  /**
   * Returns path steps
   *
   * @param boolean  True if should exclude start and end positions
   */
  public function getPoints($exclusive = false)
  {
    $points = array_reverse($this->points);
    if ($exclusive && count($points))
    {
      array_shift($points);
      array_pop($points);
    }
    return $points;
  }

  public function addPoint(MazePoint $point)
  {
    $this->points[] = $point->index();
  }

  /**
   * Checks if given point is in path
   *
   * @param  MazePoint  Maze point to check
   * @param  boolean    True if should exclude start and end positions
   *
   * @return boolean    True if given point was found in path
   */
  public function includes(MazePoint $point, $exclusive = false)
  {
    if (!count($this))
    {
      return false;
    }
    $found = in_array($point->index(), $this->points);
    if ($exclusive)
    {
      return $found
        && $point->index() != $this->points[0] // check if start point
        && $point->index() != $this->points[$this->steps()] // check if end point
      ;
    }
    return $found;
  }
}

?>
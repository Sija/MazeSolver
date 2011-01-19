<?php

class MazePoint
{
  const POS_EQUAL = 0;
  const POS_LEFT  = 2;
  const POS_RIGHT = 4;
  const POS_UP    = 8;
  const POS_DOWN  = 16;

  private $maze;
  private $idx;

  public function __construct(Maze $maze, $index)
  {
    $this->maze = $maze;
    $this->idx = $index;
  }

  public function index()
  {
    return $this->idx;
  }

  public function getPosition(self $other)
  {
    if ($this->x() < $other->x()) return self::POS_RIGHT;
    if ($this->x() > $other->x()) return self::POS_LEFT;
    if ($this->y() < $other->y()) return self::POS_DOWN;
    if ($this->y() > $other->y()) return self::POS_UP;
    // same position, shouldn't happen
    return self::POS_EQUAL;
  }

  public function getDistance(self $other)
  {
    return abs($this->x() - $other->x()) + abs($this->y() - $other->y());
  }

  public function x()
  {
    return $this->index() % $this->maze->columns();
  }

  public function y()
  {
    return (int) ($this->index() / $this->maze->columns());
  }

  /**
   * @ref self::y()
   */
  public function row()
  {
    return $this->y();
  }

  /**
   * @ref self::x()
   */
  public function col()
  {
    return $this->x();
  }
}

?>
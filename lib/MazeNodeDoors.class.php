<?php

class MazeNodeDoors extends MazeNode
{
  private $_position = null;

  /**
   * Contructs new doors node object
   *
   * @param string  String representation of the node
   * @param integer Position from which doors can be accessed
   */
  public function __construct($symbol, $position)
  {
    parent::__construct($symbol);
    $this->_position = $position;
  }

  /**
   * Returns position from which doors can be accessed
   */
  public function position()
  {
    return $this->_position;
  }

  /**
   * @return boolean True if can access (open) from given $position
   */
  public function canAccessFrom($position)
  {
    return $this->position() === $position;
  }
}

?>
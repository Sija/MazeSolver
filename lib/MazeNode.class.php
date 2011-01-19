<?php

class MazeNode
{
  private $_symbol = null;

  /**
   * Contructs anonymous node object
   *
   * @param string String representation of the node
   */
  public function __construct($symbol = null)
  {
    $this->_symbol = $symbol;
  }

  /**
   * @ref self::symbol()
   */
  public function __toString()
  {
    return $this->symbol();
  }

  /**
   * Returns string representation of the node
   */
  public function symbol()
  {
    return empty($this->_symbol)
      ? ' '
      : $this->_symbol;
  }
}

?>
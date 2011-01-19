<?php

class PQueue implements Countable
{
  private $_heap = array();

  /**
   * @ref self::count()
   */
  public function size()
  {
    return $this->count();
  }

  /**
   * @return int size of elements in queue
   */
  public function count()
  {
    return count($this->_heap);
  }

  /**
   * Adds element to the queue
   *
   * @param mixed $element
   */
  public function push($element)
  {
    $nodeId = count($this);
    $parentId = ($nodeId - 1) >> 1; // floor( ($nodeId - 1) / 2 )

    while ($nodeId != 0 && $this->_less($element, $this->_heap[$parentId]))
    {
      // Move parent node down
      $this->_heap[$nodeId] = $this->_heap[$parentId];
      // Move pointer to the next level of tree
      $nodeId = $parentId;
      $parentId = ($nodeId - 1) >> 1; // floor( ($nodeId - 1) / 2 )
    }
    // Put new node into the tree
    $this->_heap[$nodeId] = $element;
  }

  /**
   * Returns last element of the queue
   *
   * @return mixed
   */
  public function top()
  {
    if (!count($this))
    {
      return null;
    }
    return $this->_heap[0];
  }

  /**
   * Pops and returns the last element of the queue
   *
   * @return mixed
   */
  public function pop()
  {
    if (!count($this))
    {
      return null;
    }

    $top = $this->top();
    $lastId = count($this) - 1;

    /**
     * Find appropriate position for last node
     */
    $nodeId  = 0;   // Start from a top
    $childId = 1;   // First child

    // Choose smaller child
    if ($lastId > 2 && $this->_less($this->_heap[2], $this->_heap[1]))
    {
      $childId = 2;
    }

    while ($childId < $lastId && $this->_less($this->_heap[$childId], $this->_heap[$lastId]))
    {
      // Move child node up
      $this->_heap[$nodeId] = $this->_heap[$childId];

      $nodeId  = $childId;            // Go down
      $childId = ($nodeId << 1) + 1;  // First child

      // Choose smaller child
      if (($childId + 1) < $lastId && $this->_less($this->_heap[$childId + 1], $this->_heap[$childId]))
      {
        ++$childId;
      }
    }

    // Move last element to the new position
    $this->_heap[$nodeId] = $this->_heap[$lastId];
    unset($this->_heap[$lastId]);

    return $top;
  }

  /**
   * Clears queue
   */
  public function clear()
  {
    $this->_heap = array();
  }

  /**
   * Compares elements
   *
   * @param mixed $a
   * @param mixed $b
   *
   * @return boolean true, if $a is less than $b; false otherwise
   */
  protected function _less($a, $b)
  {
    return $a < $b;
  }
}

?>
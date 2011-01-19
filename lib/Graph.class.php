<?php

class Graph
{
  private $edges = array(),
          $nodes = array();

  public function addEdge($index1, $index2, $weight = 1)
  {
    $this->edges[$index1][$index2] = $weight;
  }

  public function addNode($index, &$node)
  {
    if (!isset($this->edges[$index]))
    {
      $this->edges[$index] = array();
    }
    $this->nodes[$index] =& $node;
  }

  public function getEdges($index)
  {
    return $this->edges[$index];
  }

  public function getNode($index)
  {
    return $this->nodes[$index];
  }

  public function getNodes()
  {
    return $this->nodes;
  }

  public function removeEdge($index1, $index2)
  {
    unset($this->edges[$index1][$index2]);
  }

  public function removeNode($index)
  {
    unset($this->nodes[$index]);
    unset($this->edges[$index]);

    foreach ($this->edges as &$edges)
    {
      unset($edges[$index]);
    }
  }
}

?>
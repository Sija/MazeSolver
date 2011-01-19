<?php

class Maze
{
  private
    $graph  = null,
    $cols   = null,
    $start  = null,
    $end    = null;

  public function __construct($cols = 1)
  {
    $this->graph = new Graph();
    $this->cols  = max(1, $cols);
  }

  public function point($index)
  {
    return new MazePoint($this, $index);
  }

  public function addEdge($index1, $index2, $weight = 1)
  {
    $this->graph->addEdge($index1, $index2, $weight);
  }

  public function addNode($index, MazeNode $node)
  {
    if ($node instanceof MazeNodeStart)
    {
      $this->startIndex($index);
    }
    else if ($node instanceof MazeNodeEnd)
    {
      $this->endIndex($index);
    }
    $this->graph->addNode($index, $node);
  }

  public function columns()
  {
    return $this->cols;
  }

  public function nodes()
  {
    return $this->graph->getNodes();
  }

  public function node($index)
  {
    return $this->graph->getNode($index);
  }

  public function startIndex($index = null)
  {
    if ($index === null)
    {
      return is_null($this->start)
        ? null
        : $this->start->index();
    }
    $this->start = $this->point($index);
  }

  public function endIndex($index = null)
  {
    if ($index === null)
    {
      return is_null($this->end)
        ? null
        : $this->end->index();
    }
    $this->end = $this->point($index);
  }

  public function neighbors($index)
  {
    return $this->graph->getEdges($index);
  }
}

?>

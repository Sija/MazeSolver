<?php

class AStarMazeSolver implements iMazeSolver
{
  private $maze = null;

  /**
   * Contructs Maze Solver object for a given $maze
   */
  public function __construct(Maze $maze)
  {
    $this->maze = $maze;
  }

  /**
   * Solves maze and returns constructed path (if exists)
   *
   * @return MazePath Maze Path object (can be empty)
   */
  public function solve()
  {
    $queue = new PQueue();
    $path = new MazePath();
    $maze = $this->maze;

    $startIndex = $maze->startIndex();
    $endIndex = $maze->endIndex();

    // missing either start or end point, not solvable by any chance
    if (is_null($startIndex) || is_null($endIndex))
    {
      return $path;
    }

    // array of already evaluated nodes
    $visited  = array($startIndex => true);
    // array of tentative nodes to be evaluated
    $to_visit = array();

    // push starting position into the queue
    $queue->push(array('g' => 0, 'i' => $startIndex, 'p' => null));

    // loop while queue is filled, i.e. 
    // we don't find the way out
    while (count($queue))
    {
      // get top element from the queue
      $element = $queue->pop();
      $p = $maze->point($element['i']);

      // reached end point, we can safely set up
      // path object and go home
      if ($element['i'] == $endIndex)
      {
        // walk through ancestors chain and construct the path
        while ($element)
        {
          $path->addPoint($maze->point($element['i']));
          // swap to parent element
          $element = $element['p'];
        }
        break;
      }

      // inspect immediate neighbors
      foreach ($maze->neighbors($element['i']) as $neighbor => $weight)
      {
        // determine cost of the movemement
        $path_weight = $element['g'] + $weight;
        // skip node if it'd been visitied or too expensive 
        if (isset($visited[$neighbor]) || isset($to_visit[$neighbor]))
        {
          if (isset($m) && $m['g'] <= $path_weight)
          {
            continue;
          }
        }
        // determine relative position of neighborhood point
        $pos = $maze->point($neighbor)->getPosition($p);

        // evaluate the opening of the doors
        $node = $maze->node($element['i']);
        if ($node instanceof MazeNodeDoors && !$node->canAccessFrom($pos))
        {
          continue;
        }
        // unmark as unvisited
        if (isset($to_visit[$neighbor]))
        {
          unset($to_visit[$neighbor]);
        }
        // store the new position if hadn't been stored before
        $m = array('g' => $path_weight, 'i' => $neighbor, 'p' => $element);
        if (!isset($visited[$neighbor]))
        {
          $visited[$neighbor] = true;
          $queue->push($m);
        }
      }
      $to_visit[$element['i']] = $element;
    }
    return $path;
  }
}

?>
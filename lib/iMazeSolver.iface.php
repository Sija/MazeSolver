<?php

interface iMazeSolver
{
  /**
   * Solves maze and returns constructed path (if exists)
   *
   * @return MazePath Maze Path object (can be empty)
   */
  public function solve();
}

?>
<?php

interface iMazeRenderer
{
  /**
   * Outputs rendered Maze to the end user (in whatever form you'd like to implement)
   *
   * @return string
   */
  public function output();
}

?>
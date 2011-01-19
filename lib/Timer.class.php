<?php

class Timer
{
  private $running;
  private $startTime;
  private $elapsedTime;

  public function __construct()
  {
    $this->start();
  }

  public function start()
  {
    $this->elapsedTime = 0.0;
    $this->running = true;
    $this->startTime = microtime();
  }

  public function stop()
  {
    $this->elapsedTime = $this->elapsed();
    $this->running = false;
    return $this->elapsedTime;
  }

  public function resume()
  {
    $this->running = true;
    $this->startTime = microtime();
  }

  public function reset()
  {
    $this->running = false;
    $this->startTime = null;
    $this->elapsedTime = 0.0;
  }

  public function elapsed()
  {
    if (!$this->running)
    {
      return $this->elapsedTime;
    }
    if ($this->startTime)
    {
      $start = explode(' ', $this->startTime);
      $stop  = explode(' ', microtime());
      $time  = ($stop[1] - $start[1]) + ($stop[0] - $start[0]);
      return $time + $this->elapsedTime;
    }
    return 0.0;
  }
}

?>
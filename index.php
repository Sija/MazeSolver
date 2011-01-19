<?php

/**
 * Autoloads searched class or interface
 */
function __autoload($class_name) {
  $class_path = sprintf('%s/lib/%s.class.php', dirname(__FILE__), $class_name);
  $iface_path = preg_replace('/class\.php$/', 'iface.php', $class_path);

  if (file_exists($iface_path))
  {
    return require_once $iface_path;
  }
  if (!include_once $class_path)
  {
    throw new Exception(sprintf('Cannot find class "%s"', $class_name));
  }
}

/**
 * Decorates passed arguments with layout
 *
 * @param string Template path
 * @param string String to decorate
 * @param ...
 *
 * @bug sprintf style, god will forgive me
 */
function decorate()
{
  $args = func_get_args();
  array_unshift($args, file_get_contents(array_shift($args)));
  return call_user_func_array('sprintf', $args);
}

/**
 * Splits mazes from given file
 *
 * @return array  Array with mazes (txt) or empty if none was found
 */
function getMazes($filename, $delimiter = "/(\r?\n){2,}/")
{
  if ($mazes = trim(file_get_contents($filename)))
  {
    return preg_split($delimiter, $mazes);
  }
  return array();
}

/**
 * Configuration variables
 */
$maze_file_path = './labyrinter.txt';
$layout_path    = './templates/layout.html';

// get mazes
$mazes = getMazes($maze_file_path);

// return with error if no mazes were found
if (empty($mazes))
{
  echo decorate(
    $layout_path, 
    '<div class="error">No mazes found in "<strong>labyrinter.txt</strong>" file !</div>'
  );
  return;
}

/**
 * Maze list controller; well, sort of
 */

// get selected maze and it's step
$active_maze = max(0, @$_GET['maze']);
$active_step = max(0, @$_GET['step']);

// maze list
$output = null;
$i = 0;

foreach ($mazes as $maze)
{
  $i++;
  // skip if current maze is different from selected
  if ($active_maze && $active_maze != $i)
  {
    continue;
  }
  // set up maze solving facilities
  $timer = new Timer();
  $parser = new StringMazeParser($maze);
  $maze = $parser->parse();
  $solver = new AStarMazeSolver($maze);
  $path = $solver->solve();
  $renderer = new HTMLMazeRenderer($maze, $path, $timer);

  // generate html maze code for selected step (if given)
  $maze_html = $renderer->output($active_step, $active_maze);
  // append link(s) if maze is solvable
  if ($path->exists())
  {
    // default link mask
    $link = '<a href="?maze=%d&amp;step=%d">%s</a>';
    $links = array(
      sprintf($link, $i, max(0, $active_step - 1), 'previous step'),
      sprintf($link, $i, max(0, $active_step + 1), 
        !$active_step ? 'start' : 'next step'
    ));

    // remove 'previous step' link if there's no step provided
    if (!$active_step)
      array_shift($links);
    // remove 'next step' link if we reached end
    if ($active_step >= $path->steps())
      array_pop($links);

    // append 'maze list' link if we're in detailed view
    if ($active_maze)
    {
      $links[] = sprintf($link, 0, 0, 'maze list');
    }
    $maze_html .= join(' | ', $links);
  }
  // append html maze code
  $output .= $maze_html;
}

// print output with layout
echo decorate($layout_path, $output);

?>
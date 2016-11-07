<?php
// add all controllers
require_once(dirname(__FILE__) . '/controllers/Controller.php');
require_once(dirname(__FILE__) . '/controllers/Test.php');

/**
 * The following function will strip the script name from URL
 * i.e.  http://www.something.com/search/book/fitzgerald will become /search/book/fitzgerald
 */
function getCurrentUri()
{
    $basepath = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';
    $uri      = substr($_SERVER['REQUEST_URI'], strlen($basepath));
    if (strstr($uri, '?')) $uri = substr($uri, 0, strpos($uri, '?'));
    $uri = '/' . trim($uri, '/');
    
    return $uri;
}

$base_url   = getCurrentUri();
$routes_src = explode('/', $base_url);

$routes = [];
foreach ($routes_src as $route) {
    if (trim($route) != '' and $route != 'index.php')
        array_push($routes, $route);
}

/**
 * Now, $routes will contain all the routes. $routes[0] will correspond to first route.
 * For e.g. in above example $routes[0] is search, $routes[1] is book and $routes[2] is fitzgerald
 */
switch ($routes[0]) {
    case "controller":
        if ($routes[1] == "all-tasks") {
            $controller = new Controller();
            $controller->getActiveTasks();
        }
        break;
    
    case "test":
        switch ($routes[1]) {
            case "start":
                $controller = new Test();
                $controller->start();
                break;
            
            case "add-last-tasks":
                $controller = new Test();
                $controller->addLastTasks();
                break;
            
            case "clear-tasks":
                $controller = new Test();
                $controller->clearTasks();
                break;
            
            case "clear-incoming-tasks":
                $controller = new Test();
                $controller->clearIncomingTasks();
                break;
            
            case "clear-all-tasks":
                $controller = new Test();
                $controller->clearAllTasks();
                break;
            
            case "calculate":
                $controller = new Test();
                $controller->calculate();
                break;
            
            case "add-future-tasks":
                $controller = new Test();
                $controller->addFutureTasks();
                break;
            
            case "add-non-repeatable-tasks":
                $controller = new Test();
                $controller->addNonRepeatableTasks();
                break;
            
            case "add-daily-repeatable-tasks":
                $controller = new Test();
                $controller->addDailyRepeatableTasks();
                break;
            
            case "add-weekly-repeatable-tasks":
                $controller = new Test();
                $controller->addWeeklyRepeatableTasks();
                break;
            
            case "add-monthly-repeatable-tasks":
                $controller = new Test();
                $controller->addMonthlyRepeatableTasks();
                break;
            
            case "add-yearly-repeatable-tasks":
                $controller = new Test();
                $controller->addYearlyRepeatableTasks();
                break;
        }
        break;
}


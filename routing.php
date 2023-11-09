<?php

require_once 'src/controllers/default_controller.php';

class Routing {

  public static $routes;

  public static function get($url, $controller) {
    self::$routes[$url] = $controller;
  }

  public static function run ($url) {
    $action = explode("/", $url)[0];
    if($action == '') {
        $action = 'dashboard';
    }

    if (!array_key_exists($action, self::$routes)) {
      die("Wrong url!");
    }

    $controller = self::$routes[$action];
    $object = new $controller;
    $action = $action ?: 'index';

    $object->$action();

    
  }
}
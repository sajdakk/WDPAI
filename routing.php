<?php

require_once 'src/controllers/DefaultController.php';
require_once 'src/controllers/ErrorController.php';

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
      $error = new ErrorController();
      return $error->fileNotFound();
    }

    $controller = self::$routes[$action];
    $object = new $controller;
    $action = $action ?: 'index';

    $object->$action();

    
  }
}
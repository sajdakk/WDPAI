<?php

require 'routing.php';

$path = $_SERVER["REQUEST_URI"];
$path = trim($path, "/");
$path = parse_url($path, PHP_URL_PATH);

Routing::get('', 'DefaultController');
Routing::get('login', 'DefaultController');
Routing::get('dashboard', 'DefaultController');
Routing::get('top', 'DefaultController');
Routing::get('favorites', 'DefaultController');
Routing::get('registration', 'DefaultController');
Routing::get('profile', 'DefaultController');
Routing::get('create', 'DefaultController');
Routing::get('details', 'DefaultController');

Routing::run($path);


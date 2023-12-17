<?php

require 'routing.php';

$path = $_SERVER["REQUEST_URI"];
$path = trim($path, "/");
$path = parse_url($path, PHP_URL_PATH);

Routing::get('login', 'DefaultController');
Routing::get('dashboard', 'DefaultController');
Routing::get('top', 'DefaultController');
Routing::get('favorites', 'DefaultController');
Routing::get('registration', 'DefaultController');
Routing::get('profile', 'DefaultController');
Routing::get('create', 'AddBookController');
Routing::get('details', 'DefaultController');
Routing::post('login', 'SecurityController');
Routing::post('register', 'SecurityController');
Routing::post('logout', 'SecurityController');
Routing::post('toggleFavorite', 'DefaultController');

Routing::run($path);


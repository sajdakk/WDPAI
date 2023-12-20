<?php

require 'routing.php';

$path = $_SERVER["REQUEST_URI"];
$path = trim($path, "/");
$path = parse_url($path, PHP_URL_PATH);

Routing::get('login', 'SecurityController');
Routing::get('dashboard', 'DashboardController');
Routing::get('', 'DashboardController');
Routing::get('top', 'TopController');
Routing::get('favorites', 'FavoritesController');
Routing::get('register', 'SecurityController');
Routing::get('profile', 'ProfileController');
Routing::get('admin', 'AdminController');
Routing::get('create', 'AddBookController');
Routing::get('details', 'BookDetailsController');
Routing::post('login', 'SecurityController');
Routing::post('register', 'SecurityController');
Routing::get('logout', 'SecurityController');
Routing::post('toggleFavorite', 'FavoritesController');
Routing::post('changeAvatar', 'ProfileController');
Routing::post('addReview', 'BookDetailsController');
Routing::post('toggleReviewStatus', 'AdminController');
Routing::post('toggleBookStatus', 'AdminController');
Routing::post('toggleUserStatus', 'AdminController');
Routing::post('dashboard', 'DashboardController');

Routing::run($path);


<?php
$db = new Database($config['database']);

$path = parse_url($_SERVER['REQUEST_URI'])['path'];

$routes = [
   '/' => 'controllers/home.php',
   '/blogs' => 'controllers/blogs.php',
   '/dashboard' => 'controllers/dashboard.php',
   '/login' => 'controllers/login.php',
   '/signup' => 'controllers/signup.php',
   '/logout' => 'controllers/logout.php'
];

function routeToController($path, $routes)
{
   if (key_exists($path, $routes)) require $routes[$path];
   else Abort();
}

function Abort($code = 404)
{
   http_response_code($code);
   require "views/{$code}.php";
   die();
}

routeToController($path, $routes);

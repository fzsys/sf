<?php

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();

$routes->add('home', new Route('/', [
  '_controller' => fn() => new Response('Home.'),
]));
$routes->add('leap_year', new Route('/is-leap-year/{year}', [
  'year' => NULL,
  '_controller' => 'Calendar\Controller\LeapYearController::index',
]));

return $routes;
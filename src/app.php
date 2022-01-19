<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

function is_leap_year(int $year = null): bool {
  if (null === $year) {
    $year = date('Y');
  }

  return $year % 400 === 0 || ($year % 4 === 0 && $year % 100 !== 0);
}

$routes = new RouteCollection();

$routes->add('home', new Route('/'));
$routes->add('hello', new Route('/hello/{name}', ['name' => 'World']));
$routes->add('bye', new Route('/bye'));
$routes->add('leap_year', new Route('/is-leap-year/{year}', [
  'year' => NULL,
  '_controller' => function(Request $request) {
    if (is_leap_year($request->attributes->get('year'))) {
      return new Response('This is a leap year!');
    }
    return new Response('This is not leap year.');
  },
]));

return $routes;
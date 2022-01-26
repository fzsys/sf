<?php

use App\Core;
use App\StringResponseListener;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\EventListener\ErrorListener;
use Symfony\Component\HttpKernel\EventListener\ResponseListener;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

$containerBuilder = new ContainerBuilder();

$containerBuilder->setParameter('charset', 'UTF-8');
$containerBuilder->setParameter('debug', true);
$containerBuilder->setParameter('routes', include __DIR__ . '/routes.php');

$containerBuilder->register('context', RequestContext::class);

$containerBuilder->register('matcher', UrlMatcher::class)
  ->setArguments(['%routes%', new Reference('context')]);

$containerBuilder->register('request_stack', RequestStack::class);
$containerBuilder->register('controller_resolver', ControllerResolver::class);
$containerBuilder->register('argument_resolver', ArgumentResolver::class);

$containerBuilder->register('listener.router', RouterListener::class)
  ->setArguments([
    new Reference('matcher'),
    new Reference('request_stack')
  ]);

$containerBuilder->register('listener.response', ResponseListener::class)
  ->setArguments(['%charset%']);

$containerBuilder->register('listener.exception', ErrorListener::class)
  ->setArguments(['Calendar\Controller\ErrorController::exception']);

$containerBuilder->register('listener.string_response', StringResponseListener::class);

$containerBuilder->register('dispatcher', EventDispatcher::class)
  ->addMethodCall('addSubscriber', [new Reference('listener.router')])
  ->addMethodCall('addSubscriber', [new Reference('listener.response')])
  ->addMethodCall('addSubscriber', [new Reference('listener.exception')])
  ->addMethodCall('addSubscriber', [new Reference('listener.string_response')]);

$containerBuilder->register('core', Core::class)
  ->setArguments([
    new Reference('dispatcher'),
    new Reference('controller_resolver'),
    new Reference('request_stack'),
    new Reference('argument_resolver'),
  ]);




return $containerBuilder;

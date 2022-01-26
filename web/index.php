<?php

require_once __DIR__ . ('/../vendor/autoload.php');

use Symfony\Component\HttpFoundation\Request;

/** @var \Symfony\Component\DependencyInjection\ContainerBuilder $container */
$container = include __DIR__ . '/../src/container.php';

$request = Request::createFromGlobals();
$response = $container->get('core')->handle($request);

$response->send();
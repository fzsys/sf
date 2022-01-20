<?php

namespace App;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;

/**
 * Framework core.
 */
class Core {

  /**
   * @var \Symfony\Component\Routing\Matcher\UrlMatcher
   */
  protected UrlMatcher $matcher;

  /**
   * @var \Symfony\Component\HttpKernel\Controller\ControllerResolver
   */
  protected ControllerResolver $controllerResolver;

  /**
   * @var \Symfony\Component\HttpKernel\Controller\ArgumentResolver
   */
  protected ArgumentResolver $argumentResolver;

  /**
   * Constructs new Core object.
   *
   * @param \Symfony\Component\Routing\Matcher\UrlMatcher $matcher
   * @param \Symfony\Component\HttpKernel\Controller\ControllerResolver $controllerResolver
   * @param \Symfony\Component\HttpKernel\Controller\ArgumentResolver $argumentResolver
   */
  public function __construct(UrlMatcher $matcher, 
                              ControllerResolver $controllerResolver, 
                              ArgumentResolver $argumentResolver) {
    $this->matcher = $matcher;
    $this->controllerResolver = $controllerResolver;
    $this->argumentResolver = $argumentResolver;
  }

  /**
   * Handles Request and returns Response.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *
   * @return \Symfony\Component\HttpFoundation\Response
   */
  public function handle(Request $request): Response {

    $this->matcher->getContext()->fromRequest($request);

    try {
      $request->attributes->add($this->matcher->match($request->getPathInfo()));

      $controller = $this->controllerResolver->getController($request);
      $arguments = $this->argumentResolver->getArguments($request, $controller);

      return call_user_func_array($controller, $arguments);
    } catch (ResourceNotFoundException $e) {
      return new Response('Not Found', 404);
    } catch (\Exception $e) {
      return new Response('An error occurred', 500);
    }
  }
}
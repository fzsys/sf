<?php

namespace App;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;

/**
 * Framework core.
 */
class Core {

  /**
   * @var \Symfony\Component\Routing\Matcher\UrlMatcher
   */
  protected UrlMatcherInterface $matcher;

  /**
   * @var \Symfony\Component\HttpKernel\Controller\ControllerResolver
   */
  protected ControllerResolverInterface $controllerResolver;

  /**
   * @var \Symfony\Component\HttpKernel\Controller\ArgumentResolver
   */
  protected ArgumentResolverInterface $argumentResolver;

  /**
   * @var \Symfony\Component\EventDispatcher\EventDispatcher
   */
  protected EventDispatcher $dispatcher;

  /**
   * Constructs new Core object.
   *
   * @param \Symfony\Component\Routing\Matcher\UrlMatcherInterface $matcher
   * @param \Symfony\Component\HttpKernel\Controller\ControllerResolverInterface $controllerResolver
   * @param \Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface $argumentResolver
   * @param \Symfony\Component\EventDispatcher\EventDispatcher $eventDispatcher
   */
  public function __construct(UrlMatcherInterface $matcher,
                              ControllerResolverInterface $controllerResolver,
                              ArgumentResolverInterface $argumentResolver,
                              EventDispatcher $eventDispatcher) {
    $this->matcher = $matcher;
    $this->controllerResolver = $controllerResolver;
    $this->argumentResolver = $argumentResolver;
    $this->dispatcher = $eventDispatcher;
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

      $response = call_user_func_array($controller, $arguments);
    } catch (ResourceNotFoundException $e) {
      $response = new Response('Not Found', 404);
    } catch (\Exception $e) {
      $response = new Response('An error occurred', 500);
    }

    // dispatch a response event
    $this->dispatcher->dispatch(new ResponseEvent($response, $request), 'response');

    return $response;
  }
}
<?php

namespace App;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\Event;

class ResponseEvent extends Event {
  private Request $request;
  private Response $response;

  /**
   * @param \Symfony\Component\HttpFoundation\Response $response
   * @param \Symfony\Component\HttpFoundation\Request $request
   */
  public function __construct(Response $response, Request $request) {
    $this->response = $response;
    $this->request = $request;
  }

  /**
   * @return \Symfony\Component\HttpFoundation\Response
   */
  public function getResponse(): Response {
    return $this->response;
  }

  /**
   * @return \Symfony\Component\HttpFoundation\Request
   */
  public function getRequest(): Request {
    return $this->request;
  }
}
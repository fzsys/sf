<?php

namespace Calendar\Controller;

use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Response;

class ErrorController {

  public function exception(FlattenException $e): Response {
    $msg = sprintf(
      "Something went wrong: (%s)",
      $e->getMessage()
    );

    return new Response($msg, $e->getStatusCode());
  }

}
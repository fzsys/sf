<?php

namespace App;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ContentLengthListener implements EventSubscriberInterface {

  /**
   * {@inheritDoc}
   */
  public static function getSubscribedEvents(): array {
    return ['response' => ['onResponse', -255]];
  }

  /**
   * @param \App\ResponseEvent $event
   *
   * @return void
   */
  public function onResponse(ResponseEvent $event) {
    $response = $event->getResponse();
    $headers = $response->headers;

    if (
      !$headers->has('Content-Length') && !$headers->has('Transfer-Encoding')
    ) {
      $headers->set('Content-Length', strlen($response->getContent()));
    }
  }

}
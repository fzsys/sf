<?php

namespace App;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class GoogleListener implements EventSubscriberInterface {

  /**
   * {@inheritDoc}
   */
  public static function getSubscribedEvents(): array {
    return ['response' => 'onResponse'];
  }

  /**
   * @param \App\ResponseEvent $event
   *
   * @return void
   */
  public function onResponse(ResponseEvent $event) {
    $response = $event->getResponse();

    if (
      $response->isRedirection() ||
      $response->headers->has('Content-Type') && !str_contains($response->headers->get('Content-Type'), 'html') ||
      $event->getRequest()->getRequestFormat() !== 'html'
    ) {
      return;
    }

    $response->setContent($response->getContent() . 'GA CODE');
  }

}
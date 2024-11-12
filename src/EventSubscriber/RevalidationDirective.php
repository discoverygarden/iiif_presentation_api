<?php

namespace Drupal\iiif_presentation_api\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Add 'must_revalidate' to IIIF-P manifests, for access control responsiveness.
 */
class RevalidationDirective implements EventSubscriberInterface {

  const HEADER = 'X-iiif_presentation_api_revalidate';

  /**
   * {@inheritDoc}
   */
  public static function getSubscribedEvents() : array {
    return [
      KernelEvents::RESPONSE => [['doRevalidation', -10]],
    ];
  }

  /**
   * Event callback; add in 'must_revalidate' for our controller response.
   *
   * @param \Symfony\Component\HttpKernel\Event\ResponseEvent $event
   *   The response on which to act.
   */
  public function doRevalidation(ResponseEvent $event) : void {
    $response = $event->getResponse();

    if (!$response->headers->has(static::HEADER)) {
      return;
    }

    $response->headers->remove(static::HEADER);

    if ($response->isCacheable()) {
      $response->setCache(['must_revalidate' => TRUE]);
    }
  }

}

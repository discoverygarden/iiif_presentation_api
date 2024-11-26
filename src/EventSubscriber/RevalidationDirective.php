<?php

namespace Drupal\iiif_presentation_api\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Add 'Cache-Control: no-cache' to IIIF-P manifests.
 *
 * This is done such that access control actions should take effect sooner, at
 * the expense of more load on the server during general use.
 *
 * Note: This is explicitly weighted/prioritized such that it should run _after_
 * Drupal's `FinishResponseSubscriber` runs to add its base cache headers.
 *
 * @see \Drupal\Core\EventSubscriber\FinishResponseSubscriber::getSubscribedEvents()
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
      $response->setCache(['no_cache' => TRUE]);
    }
  }

}

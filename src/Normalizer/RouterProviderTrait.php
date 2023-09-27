<?php

namespace Drupal\iiif_presentation_api\Normalizer;

use Drupal\Core\Routing\RouteProviderInterface;

/**
 * Provides a trait for injecting the route provider service.
 */
trait RouterProviderTrait {
  /**
   * The route provider service.
   *
   * @var \Drupal\Core\Routing\RouteProviderInterface
   */
  protected RouteProviderInterface $routeProvider;

  /**
   * Get the route provider service.
   *
   * @return \Drupal\Core\Routing\RouteProviderInterface
   *   The route provider service.
   */
  protected function getRouteProvider(): RouteProviderInterface {
    if (!isset($this->routeProvider)) {
      $this->routeProvider = \Drupal::service('router.route_provider');
    }
    return $this->routeProvider;
  }

  /**
   * Set the route provider service.
   *
   * @param \Drupal\Core\Routing\RouteProviderInterface $routeProvider
   *   The route provider service.
   */
  protected function setRouteProvider(RouteProviderInterface $routeProvider): void {
    $this->routeProvider = $routeProvider;
  }

}

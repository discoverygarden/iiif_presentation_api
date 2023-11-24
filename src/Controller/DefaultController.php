<?php

namespace Drupal\iiif_presentation_api\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Routing\RouteMatch;
use Symfony\Component\HttpFoundation\Request;

/**
 * Default controller; redirect to a given version of the API from base route.
 */
class DefaultController extends ControllerBase {

  /**
   * Redirect endpoint.
   *
   * @param string $destination
   *   The route to which to redirect. It is expect to take the same parameters
   *   as the current route.
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request to redirect.
   *
   * @return \Symfony\Component\HttpFoundation\RedirectResponse
   *   The redirect response.
   */
  public function defaultRedirect(string $destination, Request $request) {
    $route_match = RouteMatch::createFromRequest($request);

    $raw_params = $route_match->getRawParameters()->all();
    unset($raw_params['destination']);

    return $this->redirect(
      $destination,
      $raw_params,
    );
  }

}

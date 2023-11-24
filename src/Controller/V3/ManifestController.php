<?php

namespace Drupal\iiif_presentation_api\Controller\V3;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Routing\RouteMatch;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

class ManifestController extends ControllerBase {

  protected SerializerInterface $serializer;

  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);

    $instance->serializer = $container->get('serializer');

    return $instance;
  }

  public function build(string $parameter_name, Request $request) {
    $route_match = RouteMatch::createFromRequest($request);
    $_entity = $route_match->getParameter($parameter_name);
    return (new JsonResponse(
      $this->serializer->serialize($_entity, 'iiif-p-v3'),
      200,
      [],
      TRUE
    ));
  }

  public function titleCallback(string $parameter_name, Request $request) {
    $route_match = RouteMatch::createFromRequest($request);
    $_entity = $route_match->getParameter($parameter_name);
    return $this->t('IIIF Presentation API v3 manifest for @label', [
      '@label' => $_entity->label(),
    ]);
  }

}

<?php

namespace Drupal\iiif_presentation_api\Controller\V3;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Routing\RouteMatchInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * IIIF Presentation API V3 Manifest Controller.
 */
class ManifestController extends ControllerBase {

  /**
   * Serializer service.
   *
   * @var \Symfony\Component\Serializer\SerializerInterface
   */
  protected SerializerInterface $serializer;

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);

    $instance->serializer = $container->get('serializer');

    return $instance;
  }

  /**
   * Route content callback.
   *
   * @param string $parameter_name
   *   The parameter with the "main" entity.
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The route match object.
   */
  public function build(string $parameter_name, RouteMatchInterface $route_match) {
    $_entity = $route_match->getParameter($parameter_name);
    return (new JsonResponse(
      $this->serializer->serialize($_entity, 'iiif-p-v3'),
      200,
      [],
      TRUE
    ));
  }

  /**
   * Route title callback.
   *
   * @param string $parameter_name
   *   The parameter with the "main" entity.
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The route match object.
   *
   * @return \Drupal\Core\StringTranslation\TranslatableMarkup
   *   The title.
   */
  public function titleCallback(string $parameter_name, RouteMatchInterface $route_match) {
    $_entity = $route_match->getParameter($parameter_name);
    return $this->t('IIIF Presentation API v3 manifest for @label', [
      '@label' => $_entity->label(),
    ]);
  }

}

<?php

namespace Drupal\iiif_presentation_api\Controller\V3;

use Drupal\Core\Cache\CacheableJsonResponse;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\serialization\Normalizer\CacheableNormalizerInterface;
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
    $cache_meta = new CacheableMetadata();
    $context = [
      CacheableNormalizerInterface::SERIALIZATION_CONTEXT_CACHEABILITY => $cache_meta,
    ];
    $serialized = $this->serializer->serialize($_entity, 'iiif-p-v3', $context);
    return (new CacheableJsonResponse(
      $serialized,
      200,
      [
        'Access-Control-Allow-Credentials' => 'true',
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Methods' => 'GET',
      ],
      TRUE
    ))->addCacheableDependency($cache_meta);
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

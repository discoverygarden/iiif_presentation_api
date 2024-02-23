<?php

namespace Drupal\iiif_presentation_api;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceModifierInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Adds the iiif-p-v3 format to be available to be requested.
 */
class IiifPresentationApiServiceProvider implements ServiceModifierInterface {

  /**
   * {@inheritDoc}
   */
  public function alter(ContainerBuilder $container) {
    if ($container->has('http_middleware.negotiation') && is_a($container->getDefinition('http_middleware.negotiation')->getClass(), '\Drupal\Core\StackMiddleware\NegotiationMiddleware', TRUE)) {
      $container->getDefinition('http_middleware.negotiation')->addMethodCall('registerFormat', [
        'iiif-p-v3',
        ['application/json'],
      ]);
    }

    foreach ($container->findTaggedServiceIds('iiif_presentation_api_mapper') as $mapper_service_id => $mapper_attributes) {
      $map_service = $container->getDefinition($mapper_service_id);
      foreach ($mapper_attributes as $mapper_attributes_actual) {
        foreach ($container->findTaggedServiceIds("{$mapper_attributes_actual['base']}.{$mapper_attributes_actual['version']}") as $map_service_id => $map_attributes) {
          $map_service = $map_service->addMethodCall('addMapper', [new Reference($map_service_id)]);
        }
      }
    }
  }

}

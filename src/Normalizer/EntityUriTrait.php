<?php

namespace Drupal\iiif_presentation_api\Normalizer;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Url;

/**
 * Provides a trait for generating entity URIs.
 */
trait EntityUriTrait {

  use RouterProviderTrait;

  /**
   * Constructs the entity URI.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The entity.
   * @param array $context
   *   Normalization/serialization context.
   *
   * @return string
   *   The entity URI.
   */
  protected function getEntityUri(EntityInterface $entity, array $context = []): string {
    // Some entity types don't provide a canonical link template.
    if ($entity->isNew()) {
      throw new \LogicException('Normalizing reference to unsaved entity is not possible.');
    }

    $route_name = 'rest.entity.' . $entity->getEntityTypeId() . '.GET';
    if ($entity->hasLinkTemplate('canonical')) {
      $url = $entity->toUrl('canonical');
    }
    elseif ($this->getRouteProvider()->getRoutesByNames([$route_name])) {
      $url = Url::fromRoute('rest.entity.' . $entity->getEntityTypeId() . '.GET', [$entity->getEntityTypeId() => $entity->id()]);
    }
    else {
      throw new \LogicException("Unable to generate URL to {$entity->getEntityTypeId()} entity.");
    }

    $url->setAbsolute();
    if (!$url->isExternal()) {
      $url->setRouteParameter('_format', 'iiif-p-v3');
    }
    $generated_url = $url->toString(TRUE);
    $this->addCacheableDependency($context, $generated_url);

    return $generated_url->getGeneratedUrl();
  }

}

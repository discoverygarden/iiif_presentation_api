<?php

namespace Drupal\iiif_presentation_api\Normalizer\V3;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Url;
use Drupal\serialization\Normalizer\NormalizerBase as SerializationNormalizerBase;

/**
 * Base normalizer for IIIF Presentation API v3.
 */
abstract class NormalizerBase extends SerializationNormalizerBase {

  /**
   * {@inheritDoc}
   */
  protected $format = 'iiif-p-v3';

  /**
   * {@inheritDoc}
   */
  public function normalize($object, $format = NULL, array $context = []) {
    // XXX: No-op fallback. We expect other implementers to define normalizers
    // with a higher priority.
  }

  /**
   * {@inheritDoc}
   */
  public function supportsDenormalization($data, $type, $format = NULL) {
    // Not denormalizable.
    return FALSE;
  }

  /**
   * {@inheritDoc}
   */
  protected function checkFormat($format = NULL) {
    // The parent implementation allows format-specific normalizers to be used
    // for formatless normalization.
    return $format === $this->format;
  }

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
  protected function getEntityUri(EntityInterface $entity, array $context = []) {
    // Some entity types don't provide a canonical link template.
    if ($entity->isNew()) {
      return '';
    }

    $route_name = 'rest.entity.' . $entity->getEntityTypeId() . '.GET';
    if ($entity->hasLinkTemplate('canonical')) {
      $url = $entity->toUrl('canonical');
    }
    elseif (\Drupal::service('router.route_provider')->getRoutesByNames([$route_name])) {
      $url = Url::fromRoute('rest.entity.' . $entity->getEntityTypeId() . '.GET', [$entity->getEntityTypeId() => $entity->id()]);
    }
    else {
      return '';
    }

    $url->setAbsolute();
    if (!$url->isExternal()) {
      $url->setRouteParameter('_format', 'iiif-p-v3');
    }
    $generated_url = $url->toString(TRUE);
    $this->addCacheableDependency($context, $generated_url);
    return $generated_url->getGeneratedUrl();
  }

  /**
   * {@inheritdoc}
   */
  public function hasCacheableSupportsMethod(): bool {
    return TRUE;
  }

}

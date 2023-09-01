<?php

namespace Drupal\iiif_presentation_api\Normalizer\V3;

use Drupal\Core\Field\FieldItemListInterface;

/**
 * Normalizer for field item lists.
 */
class FieldItemListNormalizer extends NormalizerBase {

  /**
   * {@inheritDoc}
   */
  protected $supportedInterfaceOrClass = FieldItemListInterface::class;

  /**
   * {@inheritDoc}
   */
  public function normalize($object, $format = NULL, array $context = []) {
    // XXX: No-op as other implementers can define what fields to normalize
    // with a higher priority.
  }

  /**
   * {@inheritDoc}
   */
  public function getSupportedTypes(?string $format): array {
    return [
      FieldItemListInterface::class => TRUE,
    ];
  }

}

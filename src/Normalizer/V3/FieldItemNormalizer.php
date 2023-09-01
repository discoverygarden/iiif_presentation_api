<?php

namespace Drupal\iiif_presentation_api\Normalizer\V3;

use Drupal\Core\Field\FieldItemInterface;

/**
 * Normalizer for field items.
 */
class FieldItemNormalizer extends NormalizerBase {

  /**
   * {@inheritDoc}
   */
  protected $supportedInterfaceOrClass = FieldItemInterface::class;

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
      FieldItemInterface::class => FALSE,
    ];
  }

}

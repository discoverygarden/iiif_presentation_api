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
  public function getSupportedTypes(?string $format): array {
    return [
      FieldItemInterface::class => FALSE,
    ];
  }

}

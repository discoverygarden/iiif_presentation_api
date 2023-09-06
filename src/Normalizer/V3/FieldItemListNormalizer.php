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
  public function getSupportedTypes(?string $format): array {
    return [
      FieldItemListInterface::class => TRUE,
    ];
  }

}

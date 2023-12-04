<?php

namespace Drupal\iiif_presentation_api\Normalizer\V3;

use Drupal\Core\DependencyInjection\DependencySerializationTrait;
use Drupal\serialization\Normalizer\NormalizerBase as SerializationNormalizerBase;

/**
 * Base normalizer for IIIF Presentation API v3.
 */
abstract class NormalizerBase extends SerializationNormalizerBase {

  use DependencySerializationTrait;

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
  public function supportsDenormalization($data, $type, ?string $format = NULL, array $context = []) : bool {
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
   * {@inheritdoc}
   */
  public function hasCacheableSupportsMethod(): bool {
    return TRUE;
  }

}

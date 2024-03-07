<?php

namespace Drupal\iiif_presentation_api;

/**
 * Field specific normalizer interface.
 */
interface MappedFieldInterface {

  /**
   * Get the target entity type.
   *
   * @return string
   *   The target entity type.
   */
  public function getTargetEntityTypeId() : string;

  /**
   * Get the target field name.
   *
   * @return string
   *   The target field name.
   */
  public function getTargetFieldName() : string;

}

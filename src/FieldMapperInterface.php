<?php

namespace Drupal\iiif_presentation_api;

/**
 * Generic service interface in which to build out a mapping.
 */
interface FieldMapperInterface {

  /**
   * Add a type/field pair to the mapping.
   *
   * @param \Drupal\iiif_presentation_api\MappedFieldInterface $mapping
   *   The item containing the type/field pair.
   *
   * @return self
   *   Fluent interface.
   */
  public function addMapper(MappedFieldInterface $mapping) : self;

  /**
   * Get the mapping of types to field names.
   *
   * @return string[][]
   *   An associative array mapping entity type IDs to arrays of field names.
   */
  public function getMapping() : array;

  /**
   * Test if the given item is present.
   *
   * @param string $entity_type_id
   *   The entity type for which to test.
   * @param string $field_name
   *   The field name for which to test.
   *
   * @return bool
   *   TRUE if present; otherwise, FALSE.
   */
  public function isInMapping(string $entity_type_id, string $field_name) : bool;

}

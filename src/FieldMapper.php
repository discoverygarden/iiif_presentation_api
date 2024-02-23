<?php

namespace Drupal\iiif_presentation_api;

use Drupal\Core\DependencyInjection\DependencySerializationTrait;

/**
 * Help passing around some service metadata.
 */
class FieldMapper implements FieldMapperInterface {

  use DependencySerializationTrait;

  /**
   * The mapped fields for which the mapping is being built.
   *
   * @var \Drupal\iiif_presentation_api\MappedFieldInterface[]
   */
  protected array $mappers = [];

  /**
   * Memoized mapping, mapping entity type IDs to arrays of field names.
   *
   * @var string[][]
   */
  protected array $mapping = [];

  /**
   * {@inheritDoc}
   */
  public function addMapper(MappedFieldInterface $mapping) : FieldMapperInterface {
    $this->mappers[] = $mapping;
    if (!$this->isInMapping($mapping->getTargetEntityTypeId(), $mapping->getTargetFieldName())) {
      $this->mapping[$mapping->getTargetEntityTypeId()][] = $mapping->getTargetFieldName();
    }
    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function getMapping() : array {
    return $this->mapping;
  }

  /**
   * {@inheritDoc}
   */
  public function isInMapping(string $entity_type_id, string $field_name) : bool {
    return !empty($this->mapping[$entity_type_id]) && in_array($field_name, $this->mapping[$entity_type_id]);
  }

}

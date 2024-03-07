<?php

namespace Drupal\iiif_presentation_api\Event\V3;

use Drupal\Component\EventDispatcher\Event;
use Drupal\Core\Cache\RefinableCacheableDependencyInterface;
use Drupal\Core\Cache\RefinableCacheableDependencyTrait;
use Drupal\Core\Entity\EntityInterface;

/**
 * Content entity service gathering event.
 */
class ContentEntityExtrasEvent extends Event implements RefinableCacheableDependencyInterface {

  use RefinableCacheableDependencyTrait;

  /**
   * Built out set of extra properties to set in the manifest.
   *
   * @var array
   */
  protected array $extras = [];

  /**
   * Constructor.
   */
  public function __construct(
    protected EntityInterface $object,
    protected array $normalized,
    protected array $context,
  ) {}

  /**
   * Get the set extras.
   *
   * @return array
   *   The set extras.
   */
  public function getExtras() : array {
    return $this->extras;
  }

  /**
   * Get the current object being normalized, if useful.
   *
   * @return \Drupal\Core\Entity\EntityInterface
   *   The current object being normalized.
   */
  public function getObject() : EntityInterface {
    return $this->object;
  }

  /**
   * Get the current normalized data, if useful.
   *
   * @return array
   *   The current normalized data.
   */
  public function getNormalizedData() : array {
    return $this->normalized;
  }

  /**
   * Get the normalizer context, if useful.
   *
   * @return array
   *   The normalizer context.
   */
  public function getContext() : array {
    return $this->context;
  }

  /**
   * Add extra data to be included.
   *
   * @param string $type
   *   The "type" of extra data.
   * @param array $extra
   *   The extra data.
   */
  public function addExtra(string $type, array $extra) : self {
    return $this->addExtras($type, [$extra]);
  }

  /**
   * Add more complete extra data to be included.
   *
   * @param string $type
   *   The "type" of extra data.
   * @param array $extras
   *   The extra data.
   */
  public function addExtras(string $type, array $extras) : self {
    $this->extras[$type] = array_merge($this->extras[$type] ?? [], $extras);
    return $this;
  }

}

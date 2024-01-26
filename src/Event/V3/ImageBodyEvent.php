<?php

namespace Drupal\iiif_presentation_api\Event\V3;

use Drupal\Component\EventDispatcher\Event;
use Drupal\Core\Cache\RefinableCacheableDependencyInterface;
use Drupal\Core\Cache\RefinableCacheableDependencyTrait;
use Drupal\file\FileInterface;

/**
 * Image body generation event.
 */
class ImageBodyEvent extends Event implements RefinableCacheableDependencyInterface {

  use RefinableCacheableDependencyTrait;

  /**
   * The bodies added to the event.
   *
   * @var array
   */
  protected array $bodies = [];

  /**
   * Constructor.
   */
  public function __construct(
    protected FileInterface $image,
    protected string $size = 'full',
  ) {
  }

  /**
   * Get the image for which we are generating a body.
   *
   * @return \Drupal\file\FileInterface
   *   The file entity of the image for which we are generating a body.
   */
  public function getImage() : FileInterface {
    return $this->image;
  }

  /**
   * Add a body.
   *
   * @param array $body
   *   The body to add.
   */
  public function addBody(array $body) : void {
    if (!$body) {
      return;
    }
    $this->bodies[] = $body;
  }

  /**
   * Get all bodies that were added.
   *
   * @return array
   *   The bodies that were added.
   */
  public function getBodies() : array {
    return $this->bodies;
  }

  /**
   * Get the requested size hint.
   *
   * @return string
   *   The size hint requested for the event.
   */
  public function getSize() : string {
    return $this->size;
  }

}

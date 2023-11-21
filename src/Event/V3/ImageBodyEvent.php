<?php

namespace Drupal\iiif_presentation_api\Event\V3;

use Drupal\Component\EventDispatcher\Event;
use Drupal\file\FileInterface;

class ImageBodyEvent extends Event {

  protected array $bodies = [];

  /**
   * Constructor.
   */
  public function __construct(
    protected FileInterface $image,
  ) {

  }

  public function getImage() : FileInterface {
    return $this->image;
  }

  public function addBody(array $body) : void {
    $this->bodies[] = $body;
  }

  public function getBodies() : array {
    return $this->bodies;
  }

}

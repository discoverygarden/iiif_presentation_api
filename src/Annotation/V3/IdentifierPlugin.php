<?php

namespace Drupal\iiif_presentation_api\Annotation\V3;

use Drupal\Component\Annotation\Plugin;

/**
 * Identity transformation/generation plugin def.
 *
 * @Annotation
 */
class IdentifierPlugin extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public string $id;

}

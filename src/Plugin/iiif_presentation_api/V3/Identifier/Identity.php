<?php

namespace Drupal\iiif_presentation_api\Plugin\iiif_presentation_api\V3\Identifier;

use Drupal\Core\Plugin\PluginBase;
use Drupal\file\FileInterface;
use Drupal\iiif_presentation_api\Plugin\IdentifierPluginInterface;

/**
 * Identity/non-transform IIIF image ID generator.
 *
 * @IdentifierPlugin(
 *   id = "identity",
 * )
 */
class Identity extends PluginBase implements IdentifierPluginInterface {

  /**
   * {@inheritDoc}
   */
  public function getIdentifier(FileInterface $file) : string {
    return $file->createFileUrl(FALSE);
  }

}

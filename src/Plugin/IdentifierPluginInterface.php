<?php

namespace Drupal\iiif_presentation_api\Plugin;

use Drupal\file\FileInterface;

/**
 * Identifier transformation/generation plugin interface.
 */
interface IdentifierPluginInterface {

  /**
   * Generate IIIF Image API ID for the given file.
   *
   * @param \Drupal\file\FileInterface $file
   *   The file for which to generate an ID.
   *
   * @return string
   *   The generated ID.
   */
  public function getIdentifier(FileInterface $file) : string;

}

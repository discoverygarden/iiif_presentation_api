<?php

namespace Drupal\iiif_presentation_api;

use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\iiif_presentation_api\Annotation\V3\IdentifierPlugin;
use Drupal\iiif_presentation_api\Plugin\IdentifierPluginInterface;

/**
 * ID processing plugin manager service.
 */
class IdProcessPluginManager extends DefaultPluginManager {

  /**
   * Constructor.
   */
  public function __construct($version, \Traversable $namespaces, ModuleHandlerInterface $module_handler) {
    parent::__construct(
      "Plugin/iiif_presentation_api/$version/Identifier",
      $namespaces,
      $module_handler,
      IdentifierPluginInterface::class,
      IdentifierPlugin::class,
    );
  }

}

<?php

namespace Drupal\iiif_presentation_api\Routing;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Dynamic route generation.
 */
class RouteSubscriber extends RouteSubscriberBase {

  /**
   * Constructor.
   */
  public function __construct(
    protected EntityTypeManagerInterface $entityTypeManager,
  ) {

  }

  /**
   * {@inheritDoc}
   */
  protected function alterRoutes(RouteCollection $collection) {
    foreach ($this->entityTypeManager->getDefinitions() as $definition) {
      $id = $definition->id();

      // Canonical route; or, REST-provided base (just for file entities?).
      $base_route = $collection->get("entity.{$id}.canonical") ?:
        $collection->get("rest.entity.{$id}.GET");

      if (!$base_route) {
        continue;
      }

      $base_route = (clone $base_route)
        ->setRequirement('_entity_access', "{$id}.view")
        ->setOption('parameters', ($base_route->getOption('parameters') ?? []) + [
          $id => [
            'type' => "entity:{$id}"
          ],
        ]);

      $manifest_v3 = (clone $base_route)
        ->setPath("{$base_route->getPath()}/iiif-p/v3/manifest")
        ->setDefaults([
          '_controller' => 'iiif_presentation_api.v3.manifest_controller:build',
          '_title_callback' => 'iiif_presentation_api.v3.manifest_controller:titleCallback',
          'parameter_name' => $id,
        ]);
      $collection->add("entity.{$id}.iiif_p.manifest.v3", $manifest_v3);
      $canvas_v3 = (clone $base_route)
        ->setPath("{$base_route->getPath()}/iiif-p/v3/canvas/{canvas_type}/{canvas_id}")
        ->setDefaults([
          '_controller' => 'iiif_presentation_api.v3.canvas_controller:build',
          '_title_callback' => 'iiif_presentation_api.v3.canvas_controller:titleCallback',
          'parameter_name' => $id,
        ])
        ->setRequirement('_custom_access', 'iiif_presentation_api.v3.canvas_controller:access');
      $canvas_v3->setOption('parameters', $canvas_v3->getOption('parameters') + [
        'canvas_type' => [
          'type' => 'string'
        ],
        'canvas_id' => [
          'type' => 'string',
        ],
      ]);
      $collection->add("entity.{$id}.iiif_p.canvas.v3", $canvas_v3);

      // Redirect default/unversioned to the given version.
      $manifest_default = (clone $manifest_v3)
        ->setPath("{$base_route->getPath()}/iiif-p/manifest");
      $collection->add("entity.{$id}.iiif_p.manifest", $manifest_default);
      $canvas_default = (clone $canvas_v3)
        ->setPath("{$base_route->getPath()}/iiif-p/canvas/{canvas_type}/{canvas_id}");
      $collection->add("entity.{$id}.iiif_p.canvas", $canvas_default);

    }
  }

}

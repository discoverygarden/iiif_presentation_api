<?php

namespace Drupal\iiif_presentation_api\Controller\V3;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Access\AccessResultInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * IIIF Presentation API V3 Canvas Controller.
 */
class CanvasController extends ControllerBase {

  /**
   * Route content callback.
   *
   * @param string $parameter_name
   *   The parameter with the "main" entity.
   * @param string $canvas_type
   *   The entity type of the canvas.
   * @param string $canvas_id
   *   The ID of the canvas entity.
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *    The route match object.
   */
  public function build(string $parameter_name, string $canvas_type, string $canvas_id, RouteMatchInterface $route_match) {
    throw new \LogicException('Not implemented.');
  }

  /**
   * Route title callback.
   *
   * @param string $parameter_name
   *   The parameter with the "main" entity.
   * @param string $canvas_type
   *   The entity type of the canvas.
   * @param string $canvas_id
   *   The ID of the canvas entity.
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The route match object.
   *
   * @return \Drupal\Core\StringTranslation\TranslatableMarkup
   *   The title.
   */
  public function titleCallback(string $parameter_name, string $canvas_type, string $canvas_id, RouteMatchInterface $route_match) {
    $_entity = $route_match->getParameter($parameter_name);
    return $this->t('IIIF Presentation API v3 canvas of @label, for @type:@id', [
      '@label' => $_entity->label(),
      '@type' => $canvas_type,
      '@id' => $canvas_id,
    ]);
  }

  /**
   * Route custom access callback.
   *
   * @param string $parameter_name
   *   The parameter with the "main" entity.
   * @param string $canvas_type
   *   The entity type of the canvas.
   * @param string $canvas_id
   *   The ID of the canvas entity.
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The route match object.
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The account of which to check access.
   *
   * @return \Drupal\Core\Access\AccessResultInterface
   */
  public function access(string $parameter_name, string $canvas_type, string $canvas_id, RouteMatchInterface $route_match, AccountInterface $account) : AccessResultInterface {
    /** @var \Drupal\Core\Entity\EntityInterface $_entity */
    $_entity = $route_match->getParameter($parameter_name);

    try {
      $canvas_access = $this->entityTypeManager()->getStorage($canvas_type)->load($canvas_id)->access('view', $account, TRUE);
    }
    catch (\Exception $e) {
      $canvas_access = AccessResult::forbidden('Failed to load canvas entity.');
    }

    return $_entity->access('view', $account, TRUE)
      ->andIf($canvas_access);
  }

}

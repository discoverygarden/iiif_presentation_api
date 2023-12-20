<?php

namespace Drupal\iiif_presentation_api\EventSubscriber\V3;

use Drupal\Component\Plugin\PluginManagerInterface;
use Drupal\file\FileInterface;
use Drupal\iiif_presentation_api\Event\V3\ImageBodyEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * IIIF Image API body generator.
 */
class BaseImageBodyEventSubscriber implements EventSubscriberInterface {

  /**
   * Constructor.
   */
  public function __construct(
    protected PluginManagerInterface $idPluginManager
  ) {
  }

  /**
   * {@inheritDoc}
   */
  public static function getSubscribedEvents() {
    return [
      ImageBodyEvent::class => [
        ['imageV3Body', 100],
        ['imageV2Body', 75],
        ['imageV1Body', 50],
        ['baseBody', 0],
      ],
    ];
  }

  /**
   * Event callback; build base body without IIIF Image API.
   */
  public function baseBody(ImageBodyEvent $event) : void {
    $file = $event->getImage();
    $event->addBody([
      'id' => $file->createFileUrl(FALSE),
      'type' => 'Image',
      'format' => $file->getMimeType(),
      'service' => [],
    ]);
  }

  /**
   * Build out a body.
   *
   * @param string|null $slug
   *   A URL slug for the endpoint. If provided, should have an `{identifier}`
   *   portion that we will replace with an ID.
   * @param \Drupal\file\FileInterface $file
   *   The file for which to generate a bod.
   * @param array $extra
   *   An associative array of extra values to be set in the body.
   *
   * @return array
   *   The body.
   *
   * @throws \Drupal\Component\Plugin\Exception\PluginException
   */
  protected function getBody(?string $slug, FileInterface $file, array $extra = []) : array {
    if (!$slug) {
      return [];
    }

    $id_plugin = $this->idPluginManager->createInstance(getenv('IIIF_IMAGE_ID_PLUGIN') ?: 'identity');
    $base_id = strtr($slug, [
      '{identifier}' => rawurlencode($id_plugin->getIdentifier($file)),
    ]);

    return [
      'id' => "$base_id/full/full/0/default.jpg",
      'type' => 'Image',
      'format' => 'image/jpeg',
      'service' => [
        [
          // @todo Add in auth in some manner.
          'id' => $base_id,
        ] + $extra,
      ],
      'thumbnail' => [
        [
          'id' => "$base_id/full/255,/0/default.jpg",
          'type' => "Image",
        ],
      ],
    ];
  }

  /**
   * Event callback; build body for IIIF Image API v1.
   */
  public function imageV1Body(ImageBodyEvent $event) : void {
    $event->addBody($this->getBody(
      getenv('IIIF_IMAGE_V1_SLUG'),
      $event->getImage(),
      [
        'type' => 'ImageService1',
        'profile' => 'level2',
      ],
    ));
  }

  /**
   * Event callback; build body for IIIF Image API v2.
   */
  public function imageV2Body(ImageBodyEvent $event) : void {
    $event->addBody($this->getBody(
      getenv('IIIF_IMAGE_V2_SLUG'),
      $event->getImage(),
      [
        'type' => 'ImageService2',
        'profile' => 'level2',
      ],
    ));
  }

  /**
   * Event callback; build body for IIIF Image API v3.
   */
  public function imageV3Body(ImageBodyEvent $event) : void {
    $event->addBody($this->getBody(
      getenv('IIIF_IMAGE_V3_SLUG'),
      $event->getImage(),
      [
        'type' => 'ImageService3',
        'profile' => 'level2',
      ],
    ));
  }

}

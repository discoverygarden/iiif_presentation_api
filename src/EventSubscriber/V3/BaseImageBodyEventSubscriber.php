<?php

namespace Drupal\iiif_presentation_api\EventSubscriber\V3;

use Drupal\Component\Plugin\PluginManagerInterface;
use Drupal\file\FileInterface;
use Drupal\iiif_presentation_api\Event\V3\ImageBodyEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BaseImageBodyEventSubscriber implements EventSubscriberInterface {

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
        ['baseBody', 100],
        ['imageV1Body', 55],
        ['imageV2Body', 50],
        ['imageV3Body', 45],
      ],
    ];
  }

  public function baseBody(ImageBodyEvent $event) : void {
    $file = $event->getImage();
    $event->addBody([
      'id' => $file->createFileUrl(FALSE),
      'type' => 'Image',
      'format' => $file->getMimeType(),
    ]);
  }

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
      'format' => 'image/jpeg',
      'service' => [
        [
          'id' => $base_id,
//          'service' => [
//            [
//              // TODO: Define the route.
//              '@id' => Url::fromRoute(),
//              '@type' => 'AuthCookieService1',
//              'profile' => 'http://iiif.io/api/auth/1/kiosk',
//            ]
//          ],
        ] + $extra,
      ],
    ];
  }

  public function imageV1Body(ImageBodyEvent $event) : void {
    $event->addBody($this->getBody(
      getenv('IIIF_IMAGE_V1_SLUG'),
      $event->getImage(),
      [
        'type' => 'ImageService1',
        'profile' => 'level2'
      ],
    ));
  }
  public function imageV2Body(ImageBodyEvent $event) : void {
    $event->addBody($this->getBody(
      getenv('IIIF_IMAGE_V2_SLUG'),
      $event->getImage(),
      [
        'type' => 'ImageService2',
        'profile' => 'level2'
      ],
    ));
  }
  public function imageV3Body(ImageBodyEvent $event) : void {
    $event->addBody($this->getBody(
      getenv('IIIF_IMAGE_V3_SLUG'),
      $event->getImage(),
      [
        'type' => 'ImageService3',
        'profile' => 'level2'
      ],
    ));
  }

}

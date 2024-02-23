<?php

namespace Drupal\iiif_presentation_api\Normalizer\V3;

use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Url;
use Drupal\iiif_presentation_api\Event\V3\ContentEntityExtrasEvent;
use Drupal\iiif_presentation_api\FieldMapperInterface;
use Drupal\iiif_presentation_api\Normalizer\EntityUriTrait;
use Drupal\node\NodeInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * Normalizer for content entities.
 */
class ContentEntityNormalizer extends NormalizerBase {

  use EntityUriTrait;

  /**
   * {@inheritDoc}
   */
  protected $supportedInterfaceOrClass = ContentEntityInterface::class;

  /**
   * Constructor.
   */
  public function __construct(
    protected AccountInterface $user,
    protected EventDispatcherInterface $eventDispatcher,
    protected FieldMapperInterface $fieldMapper,
  ) {

  }

  /**
   * {@inheritDoc}
   */
  public function normalize($object, $format = NULL, array $context = []) {
    /** @var \Drupal\Core\Entity\EntityInterface $object */
    $normalized = [];
    if (!isset($context['base-depth'])) {
      $context['base-depth'] = TRUE;
      $normalized['@context'] = 'http://iiif.io/api/presentation/3/context.json';
    }
    else {
      $context['base-depth'] = FALSE;
    }

    if ($context['base-depth']) {
      $item_url = $object->toUrl('iiif_p.manifest');
    }
    else {
      /** @var \Drupal\Core\Entity\EntityInterface $parent */
      $parent = $context['parent']['object'];

      // XXX: We want to refer to nodes as the "canvas entities" to facilitate
      // their targeting from the IIIF-CS side of things.
      $canvas_entity = ($object instanceof NodeInterface) ?
        $object :
        $parent;

      $item_url = Url::fromRoute(
        "entity.{$parent->getEntityTypeId()}.iiif_p.canvas",
        [
          $parent->getEntityTypeId() => $parent->id(),
          'canvas_type' => $canvas_entity->getEntityTypeId(),
          'canvas_id' => $canvas_entity->id(),
        ]
      );
    }

    $generated_item_url = $item_url->setAbsolute()
      ->toString(TRUE);
    $this->addCacheableDependency($context, $generated_item_url);

    $normalized += [
      'id' => $generated_item_url->getGeneratedUrl(),
      'type' => $context['base-depth'] ? 'Manifest' : 'Canvas',
      'label' => [
        'none' => [$object->label()],
      ],
    ];

    $context += [
      'account' => $this->user,
    ];

    if (isset($context[static::SERIALIZATION_CONTEXT_CACHEABILITY])) {
      $context[static::SERIALIZATION_CONTEXT_CACHEABILITY]->addCacheContexts(['user.roles']);
    }

    $context['parent'] = [
      'type' => $normalized['type'],
      'id' => $normalized['id'],
      'object' => $object,
    ];

    /** @var \Drupal\iiif_presentation_api\Event\V3\ContentEntityExtrasEvent $service_event */
    $service_event = $this->eventDispatcher->dispatch(new ContentEntityExtrasEvent(
      $object,
      $normalized,
      $context,
    ));
    if ($extras = $service_event->getExtras()) {
      $normalized = NestedArray::mergeDeep($normalized, $extras);
    }

    return $this->normalizeEntityFields($object, $format, $context, $normalized);
  }

  /**
   * Normalizes all fields present on a content entity.
   *
   * @param \Drupal\Core\Entity\ContentEntityInterface $object
   *   The entity being normalized.
   * @param string $format
   *   The format being serialized.
   * @param array $context
   *   An array containing any context being passed to the normalizers.
   * @param array $normalized
   *   An array representing the normalized entity to be rendered.
   *
   * @return array
   *   The normalized representation of the entity.
   */
  protected function normalizeEntityFields(ContentEntityInterface $object, string $format, array $context, array $normalized): array {
    $this->addCacheableDependency($context, $object);
    foreach ($object->getFields() as $field) {
      if (!$this->fieldMapper->isInMapping($object->getEntityTypeId(), $field->getName())) {
        continue;
      }
      $access = $field->access('view', $context['account'], TRUE);
      $this->addCacheableDependency($context, $access);
      if (!$access->isAllowed()) {
        continue;
      }
      $normalized_property = $this->serializer->normalize($field, $format, $context);
      if (!empty($normalized_property)) {
        $normalized = NestedArray::mergeDeep($normalized, (array) $normalized_property);
      }
    }
    return $normalized;
  }

  /**
   * {@inheritDoc}
   */
  public function getSupportedTypes(?string $format): array {
    return [
      ContentEntityInterface::class => TRUE,
    ];
  }

}

<?php

namespace Drupal\iiif_presentation_api\Normalizer\V3;

use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Url;
use Drupal\iiif_presentation_api\Normalizer\EntityUriTrait;

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
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected AccountInterface $user;

  /**
   * Constructor for the ContentEntityNormalizer.
   *
   * @param \Drupal\Core\Session\AccountInterface $user
   *   The current user.
   */
  public function __construct(AccountInterface $user) {
    $this->user = $user;
  }

  /**
   * {@inheritDoc}
   *
   * @param \Drupal\Core\Entity\EntityInterface $object
   *   The object being serialized.
   */
  public function normalize($object, $format = NULL, array $context = []) {

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
      $item_url = Url::fromRoute(
        "entity.{$parent->getEntityTypeId()}.iiif_p.canvas",
        [
          $parent->getEntityTypeId() => $parent->id(),
          'canvas_type' => $object->getEntityTypeId(),
          'canvas_id' => $object->id(),
        ]
      );
    }

    $generated_item_url = $item_url->setAbsolute()
      ->toString(TRUE);
    $this->addCacheableDependency($context, $generated_item_url);

    $normalized += [
      //'id' => $this->getEntityUri($object, $context),
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

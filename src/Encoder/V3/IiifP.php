<?php

namespace Drupal\iiif_presentation_api\Encoder\V3;

use Drupal\serialization\Encoder\JsonEncoder;

/**
 * IIIF Presentation v3 encoder.
 */
class IiifP extends JsonEncoder {

  /**
   * The content type that this encoder supports.
   *
   * @var string
   */
  public const CONTENT_TYPE = 'application/ld+json;profile="http://iiif.io/api/presentation/3/context.json"';

  /**
   * The format that this encoder supports.
   *
   * @var array
   */
  protected static $format = ['iiif-p-v3'];

}

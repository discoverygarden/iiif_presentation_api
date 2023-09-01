<?php

namespace Drupal\iiif_presentation_api\Encoder\V3;

use Drupal\serialization\Encoder\JsonEncoder;

/**
 * IIIF Presentation v3 encoder.
 */
class IiifP extends JsonEncoder {

  /**
   * The format that this encoder supports.
   *
   * @var array
   */
  protected static $format = ['iiif-p-v3'];

}

# IIIF Presentation API

## Introduction

Provides serialization to IIIF Presentation API.

## Requirements

This module requires the following modules/libraries:

* Serialization (part of Drupal core)

## Installation

Install as usual, see
[this](https://www.drupal.org/docs/extending-drupal/installing-modules) for
further information.

## Configuration

Out of the box the module provides minimal implementation and expects other
modules to either decorate or provide their own implementation by extending the
provided normalizers.

For any content entity that should be exposed the format will need to be
configured as [documented by Drupal][1].

There are some environment variables to help integrate with IIIF Image APIs, in particular:

| Variable             | Description                                                                                       |
|----------------------|---------------------------------------------------------------------------------------------------|
| `IIIF_IMAGE_V1_SLUG` | Slug to a IIIF v1 endpoint, containing `{identifier}`, which will be replaced with an identifier. |
| `IIIF_IMAGE_V2_SLUG` | Slug to a IIIF v2 endpoint, containing `{identifier}`, which will be replaced with an identifier. |
| `IIIF_IMAGE_V3_SLUG` | Slug to a IIIF v3 endpoint, containing `{identifier}`, which will be replaced with an identifier. |
| `IIIF_IMAGE_ID_PLUGIN` | The ID of a plugin to use to transform IDs. |

Presently, we indicate `level2` compliance for each IIIF Image API endpoint.

As a point of convenience, it is possible to specify the `IIIF_IMAGE_V*_SLUG`
values using `base:`, as used by
[Drupal's `Url::fromUri()`](https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Url.php/function/Url%3A%3AfromUri/10).
For example, if you are using a proxy to make a `/iiif/2` path for a IIIF-I v2
endpoint, such that your slug would be
`https://{your hostname}/iiif/2/{identifier}`, then you can instead use
`base:/iiif/2/{identifier}` to configure the reference more explicitly relative
to the hostname used to access the site.

## Troubleshooting/Issues

Having problems or solved one? contact
[discoverygarden](http://support.discoverygarden.ca).

## Maintainers/Sponsors

Current maintainers:

* [discoverygarden](http://www.discoverygarden.ca)

Sponsor:

* [CTDA: Connecticut Digital Archive](https://lib.uconn.edu/find/connecticut-digital-archive/)

## Development

If you would like to contribute to this module create an issue, pull request
and or contact
[discoverygarden](http://support.discoverygarden.ca).

## License

[GPLv3](http://www.gnu.org/licenses/gpl-3.0.txt)

[1]: https://www.drupal.org/docs/drupal-apis/restful-web-services-api/restful-web-services-api-overview#s-api-features]

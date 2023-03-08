<?php

/**
 * @file
 * Contains \Drupal\rafflesia_events\CompaniesInterface.
 */

namespace Drupal\rafflesia_events;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;
use Drupal\Core\Entity\EntityChangedInterface;


/**
 * Provides an interface defining a Activity entity.
 * @ingroup rafflesia Events
 */

interface CompaniesInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {
  /* Update In the Future */
}

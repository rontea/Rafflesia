<?php

/**
 * @file
 * Contains \Drupal\rafflesia_events\EventsInterface.
 */

namespace Drupal\rafflesia_events;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Provides an interface defining a Event entity.
 * @ingroup rafflesia Events
 */

interface EventsInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {
  /* Update In the Future */
}

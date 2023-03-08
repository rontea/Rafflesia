<?php

/**
 * @file
 * Contains \Drupal\rafflesia_activities\ActivitiesInterface.
 */

/**
 *
 * @author rontea
 */

namespace Drupal\rafflesia_activities;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;
use Drupal\Core\Entity\EntityChangedInterface;


/**
 * Provides an interface defining a Activity entity.
 * @ingroup rafflesia Activities
 */

interface ActivitiesInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {
  /* Update In the Future */
}

<?php

/*
  Define Permission of the Entity 
 *  The permission is define in rafflesia_activities.permissions.yml
 *  */

/**
 * @file
 * Contains \Drupal\rafflesia_activities\ActivitiesAccessControlHandler.
 */

namespace Drupal\rafflesia_activities;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;


/**
 * Description of ActivitiesAccessControlHandler
 *
 * @author rontea
 */

/*
  This is use by view, edit, delete 
 *  */

class ActivitiesAccessControlHandler extends EntityAccessControlHandler {
  /**
   * {@inheritdoc}
   *
   * Link the activities to the permissions. checkAccess is called with the
   * $operation as defined in the routing.yml file.
   */
  
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    switch ($operation) {
      case 'view':
        return AccessResult::allowedIfHasPermission($account, 'view activity entity');

      case 'edit':
        return AccessResult::allowedIfHasPermission($account, 'edit activity entity');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete activity entity');
    }
    return AccessResult::allowed();
  }
  
  
   /**
   * {@inheritdoc}
   *
   * Separate from the checkAccess because the entity does not yet exist, it
   * will be created during the 'add' process.
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add activity entity');
  }
  
  
}
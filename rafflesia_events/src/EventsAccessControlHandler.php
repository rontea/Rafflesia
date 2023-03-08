<?php

/**
 * @file
 * Contains \Drupal\rafflesia_events\EventsAccessControlHandler.
 */

namespace Drupal\rafflesia_events;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;


/*
  This is use by view, edit, delete 
 **/

class EventsAccessControlHandler extends EntityAccessControlHandler {
  /**
   * {@inheritdoc}
   */
  
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    switch ($operation) {
      case 'view':
        return AccessResult::allowedIfHasPermission($account, 'view events');

      case 'edit':
        return AccessResult::allowedIfHasPermission($account, 'edit events');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete events');
    }
    return AccessResult::allowed();
  }

   /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add events');
  }
  
}
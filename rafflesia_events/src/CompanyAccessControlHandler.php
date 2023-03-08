<?php

/**
 * @file
 * Contains \Drupal\rafflesia_events\CompanyAccessControlHandler.
 */

namespace Drupal\rafflesia_events;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;

/*
  This is use by view, edit, delete 
**/

class CompanyAccessControlHandler extends EntityAccessControlHandler {
  /**
   * {@inheritdoc}
   */
  
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    switch ($operation) {
      case 'view':
        return AccessResult::allowedIfHasPermission($account, 'view company');

      case 'edit':
        return AccessResult::allowedIfHasPermission($account, 'edit company');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete company');
    }
    return AccessResult::allowed();
  }
  
  
   /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add company');
  }
  
}
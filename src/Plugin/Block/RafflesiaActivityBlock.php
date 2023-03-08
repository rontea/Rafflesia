<?php

/*
 * @file
 * Contains \Drupal\rafflesia\Plugin\Block\RafflesiaActivityBlock
 * 
 */

 namespace Drupal\rafflesia\Plugin\Block;
 
 use Drupal\Core\Block\BlockBase;
 use Drupal\Core\Session\AccountInterface;
 use Drupal\Core\Access\AccessResult;

 /**
 * Provides a 'Activity' List Block
 *
 * @Block(
 *   id = "rafflesia_activity_block",
 *   admin_label = @Translation("Rafflesia Activity Block"),
 *   category = @Translation("Blocks")
 * )
 */
 
 class RafflesiaActivityBlock extends BlockBase {

  /**
   * {@inheritdoc}
  */
 
   public function build(){
     return \Drupal::formBuilder()->getForm('Drupal\rafflesia\Form\RafflesiaActivityForm');
   } 
   public function blockAccess(AccountInterface $account) {
    /** @var \Drupal\node\Entity\Node $node */
    $node = \Drupal::routeMatch()->getParameter('node');
 
    $nid = $node->nid->value;
 
    if(is_numeric($nid)) {
     return AccessResult::allowedIfHasPermission($account, 'view rafflesia_userAssoc');
    }
    return AccessResult::forbidden(); 
   }
     
}
<?php

/*
 * @file
 * Contains \Drupal\rafflesia\Plugin\Block\RafflesiaManagerUserUpdateBlock
 * 
 */

 namespace Drupal\rafflesia\Plugin\Block;
 
 use Drupal\Core\Block\BlockBase;
 use Drupal\Core\Session\AccountInterface;
 use Drupal\Core\Access\AccessResult;
 
 use Drupal\Core\Database\Database;


 /**
 * Provides a 'Activity' List Block
 *
 * @Block(
 *   id = "rafflesia_manager_update_block",
 *   admin_label = @Translation("Rafflesia View List of WMS"),
 *   category = @Translation("Blocks")
 * )
 */
 
 class RafflesiaManagerUserUpdateBlock extends BlockBase  {
     
    /**
    * {@inheritdoc}
    */
     
     public function build() {
       
        
        
        $content = array();
        
        
        $content['message'] = array(
          '#markup' => $this->t('Team List'),
        );
        
        $headers = array ('First Name','Last Name', 'Email');
        $rows = array();
        $results = $this->getWSM();
        foreach ($results as $entry) {
        
        $rows[] = $entry;
        }
        
        $content['table'] = array(
            '#type' => 'table',
            '#header' => $headers,
            '#rows' => $rows,
            '#empty' => t('No entries available.'),
        ); 
        
        $content['#cache']['max-age'] = 0;
        return $content;
        
    }
    
    /**
    * {@inheritdoc}
    */
    
    public function blockAccess(AccountInterface $account) {
        /** @var \Drupal\node\Entity\Node $node */
        $node = \Drupal::routeMatch()->getParameter('node');

        $nid = $node->nid->value;

        if(is_numeric($nid)) {
         return AccessResult::allowedIfHasPermission($account, 'view rafflesia_userAssoc');
        }
        return AccessResult::forbidden(); 
   }
   /*
    * Generate The List of WSM
    */
   
   private function getWSM (){
     $conn = Database::getConnection();  
    // get user id
    $user = \Drupal::currentUser()->id(); 
    
    
    $select = $conn->select('rafflesia_assoc_user', 'u');
    $select->condition('u.managerId',$user);
    $select->join('user__field_user_infomation','au','au.entity_id=u.wsmid'); 
    $select->fields('au', ['field_user_infomation_first_name','field_user_infomation_last_name']);
    $select->join('users_field_data','bu','bu.uid=u.wsmid'); 
    $select->fields('bu', ['init']);
    
    $data = $select->execute()->fetchAll(\PDO::FETCH_ASSOC);
    
    return $data;
       
   }
   
     
     
 }

<?php
/**
 * @file
 * Contains \Drupal\rafflesia\Form\RafflesiaManagerUserForm
 * 
 */

namespace Drupal\rafflesia\Form;

use Drupal\Core\Database\Database;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\rafflesia\RafflesiaUserInformation;

/*
 * Create the Form Notice
 */

class RafflesiaUserAddForm extends FormBase{
    
/*
 * {@inheritdoc}
 */
      
  public function getFormId(){
    return 'rafflesia_assoc_user';
  }
  
  /*
   * {@inheritdoc}
   */
    
  public function buildForm(array $form, FormStateInterface $form_state){

  
    $user = \Drupal::currentUser()->id();
    $user_info = new RafflesiaUserInformation($user);
    
    $users = [];
    $users = $user_info->getAllUserAssoc();
    
    
    // list of my wsm 
    $form['message_mywsm'] = [
         '#markup' => $this->t('List of My Added WSM'),
    ];
    
    $headers = [ 'first_name' => $this->t('First Name') ,
      'last_name' => $this->t('Last Name')]; 
    
    // list of my wsm 
    $form['table'] = [
      '#type' => 'table',
      '#title' => 'users',
      '#header' => $headers,
      '#rows' => $users['names'],
      '#empty' => t('No users found'),
    ];
    
    /* List of Other WSM Association */
    
    $form['message_wsm_other_manager'] = [
         '#markup' => $this->t(' <p> List of WSM associated to other Manager :</p>'),
    ];
    
    $count =  count($users['users']['header']);
    $count_wsm = 0; 
    
    for($x=0;$x < $count;$x++){
      // list of my wsm that is associated to other manager
      
      $temp[] = [$users['users']['header'][$x][1]];
      
      $count_wsm = count($users['users']['wsm'][$x]);
      
      for($y=0;$y<$count_wsm;$y++){
        $tempuser[] = [$users['users']['wsm'][$x][$y][0],$users['users']['wsm'][$x][$y][0]];     
      }
      
      
      $form[$x.'manager'] = [
        '#type' => 'table',
        '#title' => 'manager',
        '#header' => ['Manager'],
        '#rows' => $temp,
        '#empty' => t('No users found'),
      ];

      $form[$x.'wsm'] = [
          '#type' => 'table',
          '#title' => 'wsm'.$x,
          '#header' => $headers,
          '#rows' => $tempuser,
          '#empty' => t('No users found'),
        ];
      
      
    $count_wsm = 0; 
    $tempuser = "";
    $temp = "";
    
     
    }
    
    // list of no associated wsm
    $form['message_wsm_free'] = [
         '#markup' => $this->t(' <p> All List of WSM Not Yet Associated :</p>'),
    ];
    
    
    if(empty($users['not_in'])){
      $results = array('No data Available');
      
      $form['message_no_wsm'] = array(
          '#markup' => $this->t('<p> No WSM Available </p>'),
        );
      
      
    }else{
       
      $form['message_add_wsm'] = array(
        '#markup' => $this->t('<p> Add the selected WSM To My Team </p>'),
      );

      $form['table_wsm_options'] = [
          '#type' => 'tableselect',
          '#title' => $this->t('WSM Users'),
          '#header' => $headers,
          '#options' => $users['not_in'],
          '#empty' => t('No users found'),
          ];

      $form['manager_id'] = array(
        '#type' => 'hidden',
        '#value' => $user,
      );

      $form['submit'] = array(
        '#type' => 'submit',
        '#value' => t('Save'),
      );
              
    }
  
    return $form;
  }
  
   /*
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
     
    if(!empty($form_state->getValue('table_wsm_options'))){
    
    // Find out what was submitted.
    $values = $form_state->getValue('table_wsm_options'); 
    $value = array_filter($values);   
  
       foreach( $value as $key => $entry){
        
           
          $data[$key] = $entry;  
          
         db_insert('rafflesia_assoc_user')
           ->fields(array(
          'wsmid' => $data[$key],
          'managerId' => $form_state->getValue('manager_id'),
         ))
        ->execute();
        drupal_set_message(t('WSM has been Added to My Team '. '' .''));     
            
        }
    
    }else{
      drupal_set_message(t('No WSM is Available'));   
    }
  }

}
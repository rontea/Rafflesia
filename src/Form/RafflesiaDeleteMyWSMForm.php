<?php
/**
 * @file
 * Contains \Drupal\rafflesia\Form\RafflesiaEditMyWSMForm
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

class RafflesiaDeleteMyWSMForm extends FormBase{



    /*
 * {@inheritdoc}
 */
  public function getFormId(){
    return 'rafflesia_delete_assoc_user_form';
  }
  
  
  /*
   * {@inheritdoc} 
   */
  
  public function buildForm(array $form, FormStateInterface $form_state){
   
    $user = \Drupal::currentUser()->id(); 
    
    $user_info = new RafflesiaUserInformation($user);
    
    
    $options = $user_info->getUserInformation();
    
    $form['message'] = array(
      '#markup' => $this->t('Delete the selected WSM on My List'),
    );
        
      $headers = [ 'first_name' => $this->t('First Name') ,
      'last_name' => $this->t('Last Name')]; 
        
    $form['table'] = [
      '#type' => 'tableselect',
      '#title' => $this->t('WSM Users'),
      '#header' => $headers,
      '#options' => $options,
      '#empty' => t('No users found'),
    ];
    
   $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Delete'),
    );
    
    
    return $form;
  }

   /*
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
 
    if(!empty($form_state->getValue('table'))){
       $table = $form_state->getValue('table');
       $id = array_filter($table);
       $data = array();
       foreach($id as $key => $entry){
        
        $data[$key] = $entry;
        $assoc_delete = db_delete('rafflesia_assoc_user')
        ->condition('wsmid', $data[$key])
        ->execute();
        
        drupal_set_message(t('WSM has been removed From My Team '. $data[$key] .'')); 
    }
 
    }else{
        drupal_set_message('No WSM on your Team');
    } 
      
  }

}

<?php
/**
 * @file
 * Contains \Drupal\rafflesia\Form\RafflesiaTaskTrackerForm
 * 
 */

namespace Drupal\rafflesia\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\rafflesia\RafflesiaActivityListing;
use Drupal\rafflesia\RafflesiaUserInformation;

/*
 * Create the Form Notice
 */

class RafflesiaTaskTrackerForm extends FormBase{
    
    
/*
 * {@inheritdoc}
 */
  public function getFormId(){
    return 'rafflesia_tasks_tracker_form';
  }
  
  /*
   * {@inheritdoc}
   */
    
  public function buildForm(array $form, FormStateInterface $form_state){
    

    $user = \Drupal::currentUser()->id();
    
    /* Get Information if Manager */
    
    
    $user_info = new RafflesiaUserInformation($user);
    $activities = new RafflesiaActivityListing($user);
    
    $results = [];
    $managerid = [];
    $activities_data = [];
    
    if($user_info->checkUserManager()){
     
     $form['message'] = array(
         '#markup' => $this->t("This is My List of Activities As Im A Manager <br>"),
        );   
     $activities_data = $activities->getManActList();
     
     foreach ($activities_data as  $entry){
        $results[$entry['id']] = $entry['activity_name'];
       
     }
      $managerid = $user;
        
    }else{
      $activities_data = $activities->getActList(); 
              
      foreach ($activities_data as  $entry){
        $results[$entry['id']] = $entry['activity_name'];
        $managerid = $entry['managerid'];
      }
    }
    

    $form['activity_name'] = array(
      '#type' => 'select',
      '#title' => t('Activity Name'),
      '#options' => $results,
      '#required' => TRUE,
    );
    
    $form['text'] = array(
      '#type' => 'textfield',
      '#title' => 'Subject',
      '#maxlength' => 500,
      '#required' => TRUE, 
    );
    
    $form['volume'] = array(
      '#type' => 'number',
      '#title' => t('Volume'),
      '#min' => 1,
      '#max' => 100,
      '#required' => TRUE,
      '#default_value' => 1, 
    );
    
    $form['manager_id'] = array(
      '#type' => 'hidden',
      '#value' => $managerid,
    );
    
    $form['user'] = array(
      '#type' => 'hidden',
      '#value' => $user,
    );
    
    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Record'),
    );
   
    return $form;
  }
  
   /*
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    
     $user = $form_state->getValue('user'); 
     $timeStamp = time();
     
    if(!empty($form_state->getValue('activity_name'))){
      db_insert('rafflesia_tasks')
        ->fields(array(
          'activity_id' => $form_state->getValue('activity_name'),
          'tasks_subject' => $form_state->getValue('text'),
          'tasks_volume' => $form_state->getValue('volume'),
          'uid' => $user,
          'manager_id' => $form_state->getValue('manager_id'),
          'tasks_date' => date('Y-m-d',$timeStamp) ,
          'tasks_timestamp' => $timeStamp,
          'activity_archive' => 0,
         )) 
        ->execute();
      drupal_set_message(t('Complete Task Track :'. $form_state->getValue('text') ));
      
    }else{
      drupal_set_message(t('Cannot Track Task No Activity has been define'));   
    }
    
  }
}
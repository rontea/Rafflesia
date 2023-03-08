<?php
/**
 * @file
 * Contains \Drupal\rafflesia\Form\MyActivityEdit
 * 
 */


namespace Drupal\rafflesia\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\rafflesia\RafflesiaActivityListing;
use Drupal\rafflesia\RafflesiaUserInformation;
use Drupal\Core\Url;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\rafflesia\RafflesiaTasks;

/*
 * Create the Form Notice
 */

class MyActivityEdit extends FormBase{
    
 /**
  *
  * @var type 
  */
  
 private $path; 

 /**
  *
  * @var type 
  */
 
 private  $to;

 /**
 *
 * @var type 
 */ 
 
 private $from;

  
 /*
 * {@inheritdoc}
 */
      
  public function getFormId(){
    return 'editMyForm';
  }
  
  /*
   * {@inheritdoc}
   * 
   * @param int $id
   * @param int $path
   * @param date $from
   * @param date $to
   */
    
  public function buildForm(array $form, FormStateInterface $form_state, 
      $id = NULL, $path = NULL, $from = NULL, $to = NULL){
    
    /* Make Sure that When URL is Editted it is protected */
    if($path >= 2){
      drupal_set_message(t('Edit Info is not allowed'));
      
    }else{
    
        $user = \Drupal::currentUser()->id();

        /* Cast Value for Clean Data */
        $cast_id = $id;
        $cast_from = $from;
        $cast_to = $to;


        // set the path
        $this->path = $path;
        $this->from = $cast_from;
        $this->to = $cast_to;


        /* Get Information if Manager */
        $user_id = new RafflesiaTasks($cast_id);

        /* Check if The User is Authenticated to Edit */
        if($user != $user_id->getId()){
          drupal_set_message('Access Denied: The Activity is not on your account.');
        }else {

          // list of my wsm 
          $form['message_top'] = [
               '#markup' => $this->t('Edit This : @id, <br><br>', [ '@id' => $id ] ),
          ];

          $user_info = new RafflesiaUserInformation($user);
          $activities = new RafflesiaActivityListing($user);

          $results = [];
          $managerid = [];
          $activities_data = [];
          $default_act = [];

          /* Check if The user is a Manager */
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

          $default_act = $activities->getAct($id);

          $form['activity_name'] = array(
            '#type' => 'select',
            '#title' => t('Activity Name'),
            '#options' => $results,
            '#default_value' => $default_act['activity_id'] ,
            '#required' => TRUE,
          );

          /* Data And Time Format */

          $date_format = 'd-m-Y';
          $time_format = 'h:i A'; 
          $today = date($date_format . ' ' . $time_format  ,  $default_act['tasks_timestamp']);

          /* Date Form */

          $form['date_time'] = array(
            '#type' => 'datetime',
            '#title' => t('Date  Time'),  
            '#date_time_format' => $date_format .''. $time_format ,
            '#description' => '* enter the new date and time', 
            '#default_value' => new DrupalDateTime($today),  
            '#required' => TRUE,  
          );

           $form['activity_subject'] = array(
            '#type' => 'textfield',
            '#title' => 'Subject',
            '#maxlength' => 500,
             '#default_value' => $default_act['tasks_subject'],
            '#required' => TRUE, 
          );

          $form['volume'] = array(
            '#type' => 'number',
            '#title' => t('Volume'),
            '#min' => 1,
            '#max' => 100,
            '#required' => TRUE,
            '#default_value' => $default_act['tasks_volume'], 
          );

           // Extra actions for the display.
          $form['actions']['other_action'] = [
            '#type' => 'dropbutton',

          ];

          $form['path'] = array(
            '#type' => 'hidden',
            '#value' => $this->path,
          );

          $form['from'] = array(
            '#type' => 'hidden',
            '#value' => $this->from,
          );

          $form['to'] = array(
            '#type' => 'hidden',
            '#value' => $this->to,
          );     

          $form['actions']['cancel'] = array(
            '#type' => 'submit',
            '#submit' => array([$this, 'cancelForm']),
            '#value' => t('Cancel'),
          );

          $form['submit'] = array(
            '#type' => 'submit',
            '#value' => t('Save Update'),
          );

          $form['id'] = array(
            '#type' => 'hidden',
            '#value' => $id,
          );
        }
    
    }
    return $form;   
  }
  public static function cancelForm(array &$form, FormStateInterface $form_state) {
    
    $path = $form_state->getValue('path');
    $from =  $form_state->getValue('from');
    $to =  $form_state->getValue('to');
    
    try{  
        if($path == 0){
           $url = \Drupal\Core\Url::fromRoute('rafflesia.report_today');
        }else if ($path == 1) {
          $url = \Drupal\Core\Url::fromRoute('rafflesia.report_individual')
                ->setRouteParameters(array('from'=>$from,'to'=>$to));
        }else{
          drupal_set_message(t('Edit Info is not allowed'));
        }

        $form_state->setRedirectUrl($url);

        }catch (\Exception $e){
           drupal_set_message('Error '. $e->getMessage());
        }
    }
 
   /*
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
     
    $timeStamp =  strtotime($form_state->getValue('date_time'));
    $from =  $this->from;
    $to =  $this->to;
    
    try{ 
      
      db_update('rafflesia_tasks')
        ->fields( array(
          'activity_id' => $form_state->getValue('activity_name'), 
          'tasks_subject' => $form_state->getValue('activity_subject'),
          'tasks_volume' => $form_state->getValue('volume'),
          'tasks_date' => date('Y-m-d',$timeStamp) ,
          'tasks_timestamp' => $timeStamp,
        ))
        ->condition('id',$form_state->getValue('id') , '=')
      ->execute();
  
    }catch (\Exception $e){
           drupal_set_message('Error '. $e->getMessage());
        }
        
    try{
      if($this->path == 0){
         $url = \Drupal\Core\Url::fromRoute('rafflesia.report_today');
       }else if($this->path == 1){
         $url = \Drupal\Core\Url::fromRoute('rafflesia.report_individual')
              ->setRouteParameters(array('from'=>$from,'to'=>$to));
       }else{
         drupal_set_message(t('Edit Info is not allowed'));
       } 

       $form_state->setRedirectUrl($url);

    } catch (\Exception $e){
       drupal_set_message('Error '. $e->getMessage());
    }
    
    drupal_set_message(t('Edit Completed on Task'));
    
  }

}
<?php

namespace Drupal\rafflesia\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\rafflesia\RafflesiaTasks;
use Drupal\user\Entity\User;

class MyActivityDelete extends ConfirmFormBase {

  /**
   * The ID of the item to delete.
   *
   * @var string
   */
  protected $id;
  
  /**
   * 2 Path 0  or 1 for today report and report with date param
   *
   * @var type int
   */
  
  private $path;
  
  /**
   * Param Route Date
   * 
   * @var type date 
   */
  
  private $from;
  
  /**
   * Param Route Date
   *
   * @var type date
   */
  
  private $to;
  
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'rafflesia_myActivity_delete';
  }
 
  /**
  * {@inheritdoc}
  */
  public function getQuestion() {
    /* Get the last URL */
     
    return t('Do you want to delete %id?' . ' ', array('%id' => $this->id));
  }
  
  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
  
  /* Check Path */  
  $path = $this->path;
  
  /* Check What Path to go */
      try{
          if($path == 0){
              $url = \Drupal\Core\Url::fromRoute('rafflesia.report_today');
          }else{
            $from = $this->from;
            $to = $this->to;
            $url = \Drupal\Core\Url::fromRoute('rafflesia.report_individual')
                       ->setRouteParameters(array('from'=>$from,'to'=>$to));
          }
      }catch(\Exception $e){
        drupal_set_message('Missing Database Exception '. $e->getMessage());
      }  
  return $url;
  }
  
  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return t('Do this if you are sure.');
  }
  
  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return t('Delete');
  }

 /**
   * {@inheritdoc}
   */
  public function getCancelText() {
    return t('Cancel');
  }
  
  /**
   * {@inheritdoc}
   *
   * @param int $id
   *   The ID of the item to be deleted.
   * @param int $path
   * @param date $from
   * @param date $to
   */
  
  public function buildForm(array $form, FormStateInterface $form_state , 
        $id = NULL, $path = NULL, $from = NULL, $to = NULL) {
    
    /* Build Data */
    
    $cast_from = $from;
    $cast_to = $to;
    
    /* Set The Data */
    $this->id = $id;
    $this->path = $path;
    
    if($path == 1){
      $this->from = $cast_from;
      $this->to = $cast_to;
    }
   
    
    if($path >= 2){
      
      drupal_set_message(t('Edit Info is not allowed'));
      
    }else{
        $user_id = \Drupal::currentUser()->id();

        $check_info = new RafflesiaTasks($this->id);

        $user =  User::load($id);

        $uid = $check_info->getId();

        // Auto Generate Field
        if($user_id == $uid){

          return parent::buildForm($form, $form_state);

        }else{

          drupal_set_message('Access Denied: The Activity is not on your account access denied');
        } 
        
    }    
  }
   /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    
    $id = $this->id;
    $path = $this->path;
  
   /* Do the Delete */ 
   try { 
    $delete = db_delete('rafflesia_tasks')
      ->condition('id', $id)
      ->execute();
      }catch(\Exception $e){
          drupal_set_message('Missing Database Exception '. $e->getMessage());
      }     
   /* Check What path to go back to */
    try {           
      
          if($path == 0){
              $url = \Drupal\Core\Url::fromRoute('rafflesia.report_today');
           }else{
             $from = $this->from;
             $to = $this->to;
             
             $url = \Drupal\Core\Url::fromRoute('rafflesia.report_individual')
                   ->setRouteParameters(array('from'=>$from,'to'=>$to));
           }
   
        $form_state->setRedirectUrl($url);

      } catch(\Exception $e){
          drupal_set_message('Missing Database Exception '. $e->getMessage());
      }
      
      drupal_set_message("Delete Completed");  
  }
  
} 
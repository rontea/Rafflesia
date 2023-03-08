<?php
/**
 * @file
 * Contains \Drupal\rafflesia\Form\RafflesiaActivityForm
 * 
 */


namespace Drupal\rafflesia\Form;

use Drupal\Core\Database\Database;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/*
 * Create the Form Notice
 */

class RafflesiaActivityForm extends FormBase{

/*
 * {@inheritdoc}
 */
  public function getFormId(){
    return 'rafflesia_activity_form';
  }
  
  /*
   * {@inheritdoc} 
   */
  
  public function buildForm(array $form, FormStateInterface $form_state){
   
    
    $form['title'] = array(
      '#type' => 'textfield',
      '#title' => 'Activity Name',
      '#maxlength' => 200,
      '#required' => TRUE, 
    );

    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Submit'),
    );
  
    
    return $form;
  }
 /*
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
      
  }
   /*
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $user = \Drupal::currentUser()->id();
    
  
    db_insert('rafflesia_activities')
      ->fields(array(
        'activity_name' => $form_state->getValue('title'),
        'uid' => $user,
        'activity_timestamp' => time(),
       ))
      ->execute();
    drupal_set_message(t('Activity Has Been Entered'));
    
  }
}
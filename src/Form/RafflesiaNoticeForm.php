<?php
/**
 * @file
 * Contains \Drupal\rafflesia\Form\RafflesiaNoticeForm
 * 
 */


namespace Drupal\rafflesia\Form;

use Drupal\Core\Database\Database;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/*
 * Create the Form Notice
 */

class RafflesiaNoticeForm extends FormBase{

/*
 * {@inheritdoc}
 */
  public function getFormId(){
    return 'rafflesia_notice_form';
  }
  
  /*
   * {@inheritdoc}
   */
  
  public function buildForm(array $form, FormStateInterface $form_state){
    
    $user = \Drupal::currentUser()->id();
      
    $form['title'] = array(
      '#type' => 'textfield',
      '#title' => 'Title of Annoucement',
      '#maxlength' => 200,
      '#required' => TRUE, 
    );
    
    $form['text'] = array(
      '#type' => 'textarea',
      '#title' => 'Notice Message',
      '#required' => TRUE, 
    );
    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Submit'),
    );
    $form['uid'] = array(
      '#type' => 'hidden',
      '#value' => $user,
    );
    
   
    return $form;
  }
   
  /*
  * {@inheritdoc}
  */
  
  public function submitForm(array &$form, FormStateInterface $form_state) {
  
    db_insert('rafflesia_notice')
      ->fields(array(
        'notice_title' => $form_state->getValue('title'),
        'notice_message' => $form_state->getValue('text'),
        'uid' => $form_state->getValue('uid'),
        'created' => time(),
       ))
      ->execute();
    drupal_set_message(t('Activity Has Been Entered'));    
  }
}
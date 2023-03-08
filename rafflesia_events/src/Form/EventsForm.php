<?php

/**
 * @file
 * Contains Drupal\rafflesia_events\Form\EventsForm.
 */

namespace Drupal\rafflesia_events\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Language\Language;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Datetime\DrupalDateTime;

use Drupal\rafflesia\RafflesiaUserInformation;

class EventsForm extends ContentEntityForm {
  
  /**
   * {@inheritdoc}
   */
  
  public function buildForm(array $form, FormStateInterface $form_state) {
   
    $form = parent::buildForm($form, $form_state);
    $entity = $this->entity;
    
    $form['langcode'] = array(
      '#title' => $this->t('Language'),
      '#type' => 'language_select',
      '#default_value' => $entity->getUntranslated()->language()->getId(),
      '#languages' => Language::STATE_ALL,
    );
    
    // Get The user 
    $userid = \Drupal::currentUser()->id();
    
    $form['uid'] = array(
      '#type' => 'hidden',
      '#value' => $userid,
    );
    /* Add the user information */
    $user_information = new RafflesiaUserInformation($userid);
    
    $user_info = $user_information->getSingleUser();
    
    $form['name'] = array(
      '#type' => 'hidden',
      '#value' => $user_info,
    );
    
    /* Data And Time */
    
    $date_format = 'd-m-Y';
    $time_format = 'h:i A';
    
    $format = "m/d/Y";  
    $today = date($date_format . ' ' . $time_format  ,  time());
    
    /* Date Form */
    
    $form['event_date'] = array(
      '#type' => 'datetime',
      '#title' => t('Date Time of Event'),  
      '#date_format' => $format,
      '#description' => '* enter the date and time of the event, Please note that this is on ET ' , 
      '#date_time_format' => $date_format .''. $time_format ,
      '#default_value' => new DrupalDateTime($today),
      '#required' => FALSE,  
    );
    
    return $form;
  }
  
  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    
    $form_state->setRedirect('entity.events.collection');
    $entity = $this->getEntity();
    $entity->save();
  }

  
}

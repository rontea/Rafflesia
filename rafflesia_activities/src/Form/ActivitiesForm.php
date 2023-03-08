<?php

/**
 * @file
 * Contains Drupal\rafflesia_activities\Form\ActivitiesForm.
 */

namespace Drupal\rafflesia_activities\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Language\Language;
use Drupal\Core\Form\FormStateInterface;

use Drupal\rafflesia\RafflesiaUserInformation;

/**
 * Description of ActivitiesForm
 *
 * @author rontea
 */
class ActivitiesForm extends ContentEntityForm {
  
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
    
    $user_information = new RafflesiaUserInformation($userid);
    
    $user_info = $user_information->getSingleUser();
    
    $form['name'] = array(
      '#type' => 'hidden',
      '#value' => $user_info,
    );
    
    return $form;
  }
  
  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $form_state->setRedirect('entity.activities.collection');
    $entity = $this->getEntity();
    $entity->save();
  }

  
}

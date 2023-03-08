<?php

/**
 * @file
 * Contains Drupal\rafflesia_events\Form\EventsSettingsForm.
 */

namespace Drupal\rafflesia_events\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class EventsSettingsForm extends FormBase {
  
  /**
   * {@inheritdoc}
   */
  
  public function getFormId() {
    return 'rafflesia_events_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Empty implementation of the abstract submit class.
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['events_settings']['#markup'] = 'Settings form for Events Setting. Manage field settings here.';
    return $form;
  }

}

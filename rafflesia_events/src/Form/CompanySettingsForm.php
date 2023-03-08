<?php

/**
 * @file
 * Contains \Drupal\rafflesia_events\Form\CompanySettingsForm.
 */

namespace Drupal\rafflesia_events\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;


class CompanySettingsForm extends FormBase {
  
  /**
   * {@inheritdoc}
   */
  
  public function getFormId() {
    return 'rafflesia_events_company';
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
    $form['company_settings']['#markup'] = 'Settings form for Company Setting. Manage field settings here.';
    return $form;
  }

}

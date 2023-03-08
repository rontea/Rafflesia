<?php

/**
 * @file
 * Contains Drupal\rafflesia_events\Form\SearchCompanyForm.
 */


namespace Drupal\rafflesia_events\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\rafflesia\ReportChecker;


class SearchCompanyForm extends FormBase{

 /**
 * {@inheritdoc}
 */

  public function getFormId() {
    return 'search_company_form';
  }
  
  /**
   * {@inheritdoc}
   */
  
  public function buildForm(array $form, FormStateInterface $form_state) {
    
  }
  
  public function submitForm(array &$form, FormStateInterface $form_state) {
    
  }
  
}

<?php

/**
 * @file
 * Contains \Drupal\rafflesia\Form\RafflesiaMyReportForm
 * 
 */

namespace Drupal\rafflesia\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\rafflesia\ReportChecker;

class RafflesiaMyReportForm extends FormBase{
  
  /**
   * 
   * {@inheritdoc}
   */
  
  public function getFormId() {
    return 'my_report_form';
  }
  
  /**
   *
   * {@inheritdoc}
   */
  
  public function buildForm(array $form, FormStateInterface $form_state) {
    
    $format = "Y-m-d";
    $today = date('Y-m-d',time());
    
    // Radios.
    $form['selection'] = [
      '#type' => 'radios',
      '#title' => t('Report Types'),
      '#options' => [0 => $this->t('My Report'), 1 => $this->t('Team'), 2 => $this->t('Stacked Report')],
      '#description' => $this->t('This is the different option on report'),
    ];
    
    
    $form['selectForm'] = array(
      '#markup' => $this->t('Select the Date'),
    );
    /* Date Time Form */
    
    $form['from'] = array(
      '#type' => 'date', 
      '#title' => t('Select Date From'), 
      '#date_date_format' => $format,
      '#default_value' => $today,
      '#required' => TRUE,
    );
    
    $form['to'] = array(
      '#type' => 'date', 
      '#title' => t('Select Date To'), 
      '#date_format' => $format,
    );
    
    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Generate'),
    );
    
    return $form;
    
  }
  
  /**
   *
   * {@inheritdoc}
   */
  
  public function submitForm(array &$form, FormStateInterface $form_state) {
    
    $from = $form_state->getValue('from');
    $to =  $form_state->getValue('to');
    $selection = $form_state->getValue('selection');
    
    $check = new ReportChecker($from,$to);
    
    if(empty($from)){
      $from = date('Y-m-d',time());
    }
    
    if($check->checkProceed() && $selection == 0 ){
    
      $url = \Drupal\Core\Url::fromRoute('rafflesia.report_individual')
            ->setRouteParameters(array('from'=>$from,'to'=>$to));

       $form_state->setRedirectUrl($url);
       
    }else if($check->checkProceed() && $selection == 1){
   
         $url = \Drupal\Core\Url::fromRoute('rafflesia.report_team')
            ->setRouteParameters(array('from'=>$from,'to'=>$to));
         $form_state->setRedirectUrl($url);
           
    }else if($check->checkProceed() && $selection == 2){
   
         $url = \Drupal\Core\Url::fromRoute('rafflesia.report_team_stacked')
            ->setRouteParameters(array('from'=>$from,'to'=>$to));
         $form_state->setRedirectUrl($url);
           
    }
    else{
      
       drupal_set_message("Missing Parameters or 'From' is Greaterthan 'To' Cannot Proceed Please Check Your Inputs!");
    }
    
   
     
  }

}

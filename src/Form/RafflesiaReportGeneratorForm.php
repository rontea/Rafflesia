<?php
/**
 * @file
 * Contains \Drupal\rafflesia\Form\RafflesiaReportGeneratorForm
 * 
 */

namespace Drupal\rafflesia\Form;

use Drupal\rafflesia\RafflesiaUserInformation;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\rafflesia\RafflesiaActivityListing;

/*
 * Create the Form Notice
 */

class RafflesiaReportGeneratorForm extends FormBase{
    
    /*
     * Store the User type
     */
    private $data = false;
    
    /*
     * Temporary data holder for the list of WSM
     */
    private $tempArray = array();
    
/*
 * {@inheritdoc}
 */
  public function getFormId(){
    return 'rafflesia_generator_report_form';
  }
  
  /*
   * {@inheritdoc}
   */
    
  public function buildForm(array $form, FormStateInterface $form_state){
    
    $user = \Drupal::currentUser()->id();
    RafflesiaUserInformation::setUser($user);
    
    // check the type
    $check = RafflesiaUserInformation::checkUserManager();
    $this->data = $check;
   
    // date format
    $format = 'mm-dd-Y';
    $today = time();
   
    
    if($check){
    
      // Qutput all the Users under the manager
    $header = [ 'first_name' => $this->t('First Name') ,
      'last_name' => $this->t('Last Name')];   
    $options = RafflesiaUserInformation::getUserInformation();
    
    $this->tempArray = $options;
    
    $form['wsm'] = array(
      '#type' => 'tableselect',
      '#header' => $header,
      '#options' => $options,
      '#empty' => $this->t('No WSM under my supervision is listed'),
    );    
        
        
    }else{
       
      $header = [$this->t('My Report'),""];
      
      $options = RafflesiaUserInformation::getIndividualInformation();
      
      $this->tempArray = $options;
      
      $form['wsm'] = array(
        '#type' => 'table',
        '#header' => $header,
        '#rows' => $options,
        '#empty' => $this->t('Error On Report'),
      ); 
     
    }
     
    
    /* Manage Report */
    
    $form['from'] = array(
      '#type' => 'date', 
      '#title' => t('Select Date From'), 
      '#date_date_format' => $format,
      '#default_value' => $today,
    );
    
    
    $form['to'] = array(
      '#type' => 'date', 
      '#title' => t('Select Date To'), 
      '#date_format' => $format,
      
    );

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Generate Report'),
    );
    
    return $form;
  }
  
   /*
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    
    $to = $form_state->getValue('to');
    $from = $form_state->getValue('from');
    
      if(empty($from) && empty($to)){
          drupal_set_message(t('Date is not Specified'));
          
      }else if(empty($to)){
          
          $wsm = $form_state->getValue('wsm');
          $listWsm = RafflesiaUserInformation::getWsmClean($wsm);
          
          
          // no option is selected
          if(empty($listWsm)){
            $listWsm = $this->tempArray; 
              
            
            foreach($listWsm as $key => $display){

            drupal_set_message(t('Report For: '.$listWsm[$key]['first_name']));
           
            }
        
          }else{
           
            
            // display the name of WSM
            foreach($listWsm as $key => $display){

            drupal_set_message(t('Report For: '.$this->tempArray[$key]['first_name']));
               
            }
            // generate only the day report
           
          }
          
        drupal_set_message(t('Report generated for this day '.$from));  
          
          
      }else if(!empty($to) && !empty($from)){
          $wsm = $form_state->getValue('wsm');
          $listWsm = RafflesiaUserInformation::getWsmClean($wsm);
          if($to < $from){
                  
                  drupal_set_message('Invalid From Cannot Be Lesser than To');
                  
          }else{ 
            if($to != $from){
          
                if(empty($listWsm)){
                    // generate all

                  $listWsm = $this->tempArray;  
                  foreach($listWsm as $key => $display){

                  drupal_set_message(t('Report For: '.$listWsm[$key]['first_name']));

                  }


                }else{

                    // display the name of WSM
                  foreach($listWsm as $key => $display){

                  drupal_set_message(t('Report For: '.$this->tempArray[$key]['first_name']));

                  }
                } 
                
                drupal_set_message(t('Report generated for this day '.$from." to ".$to));
            }else{
                // generate today report 
                  if(empty($listWsm)){
                    $listWsm = $this->tempArray; 


                    foreach($listWsm as $key => $display){

                    drupal_set_message(t('Report For: '.$listWsm[$key]['first_name']));

                    }
                  }else{
                    // display the name of WSM
                    foreach($listWsm as $key => $display){

                    drupal_set_message(t('Report For: '.$this->tempArray[$key]['first_name']));

                    }
                    // generate only the day report

                  }
                  
                  drupal_set_message(t('Report generated for this day '.$from));
            }
          }
      }else{
          
          if(empty($from) && !empty($to)){
              drupal_set_message(t("Cannot Generate From date is missing"));
          }
          
      }
    
  }
 
}
<?php
/**
* @file
* Contains \Drupal\mymodule\Controller\CalendarController class.
*/
namespace Drupal\rafflesia\Controller;

use Drupal\Core\Database\Database;
use Drupal\Core\Controller\ControllerBase;
use Drupal\rafflesia\RafflesiaGetReport;
use Drupal\rafflesia\RafflesiaUserInformation;

/**
* Calendar Reports
*/

class ReportController extends ControllerBase {
  
  /*
   * Build The Calendar JS
   **/
  
  public function build($from,$to){
    
    $dateFormat = 'Y-m-d';
    $timeFormat = 'g:i:s A';
    
    $user_id = \Drupal::currentUser()->id();
    
    $report = new RafflesiaGetReport($from,$to);
    $report->setUser($user_id);
    $report->setToTeam(0);
   
    $action = $report->generateAction();
     
    drupal_set_message($action);
    
    /* Build Info */
     if(empty($to)){
        $build['message'] = array(
         '#markup' => $this->t('This is the result of Date only @from :', [ '@from' => $from ]),
        );
     }else{
       $build['message'] = array(
         '#markup' => $this->t('This is the result of From @from and To @to :', [ '@from' => $from , '@to' => $to ]),
        );
     }
    
     /* Build Headers */
     
     $headers = array(
        t('ID'),
        t('Activity Name'),
        t('Subject'),
        t('Volume'),
        t('Time'),
        t('Update'),
        t('Delete'),
       
      );
     
     $headers_total = array (
        t('ID'),
        t('Activity'),
        t('total'), 
      );
     
     $headers_grandTotal = array (
        t('Over All Total'),
     );
     
     /* Associate Data */
     
    $row_table_activities = $report->getData();
    $rows_activities = $report->getAWTotal();
     
     /* Build Data */
     
     $build['table_taks'] = array(
      '#type' => 'table',
      '#header' => $headers,
      '#rows' => $row_table_activities,
      '#empty' => t('No entries available.'),
    ); 
     
     $build['table_activities'] = array(
      '#type' => 'table',
      '#header' => $headers_total,
      '#rows' => $rows_activities,
      '#empty' => t('No entries available.'),
    ); 
     
     $total_info[] = array( 'total' => $report->getVolumeTotal());
     
     $build['table_total_tasks'] = array(
      '#type' => 'table',
      '#header' => $headers_grandTotal,
      '#rows' => $total_info,
      '#empty' => t('No entries available.'),
    ); 
   
    $build['#cache']['max-age'] = 0; 
    return $build;   
  }
 
}
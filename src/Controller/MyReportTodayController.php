<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Drupal\rafflesia\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\rafflesia\RafflesiaGetReport;

/**
 * Description of MyReportTodayController
 *
 * @author rontea
 */

class MyReportTodayController extends ControllerBase  {
  
  /*
   * Display The Report
   **/  
    
  public function reportToday(){
    
    $dateFormat = 'Y-m-d';
    $timeFormat = 'g:i:s A';
    
    
    $from = date( $dateFormat , time());
    $to = "";

    $userid = \Drupal::currentUser()->id();
    $reportInfo = new RafflesiaGetReport($from,$to);
    $reportInfo->setUser($userid);
    $action = $reportInfo->generateAction();
    
    /* Build Message */
    $create['message'] = array(
         '#markup' => $this->t('This is the result For Today: @from', [ '@from' => $from]),
        );
    
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
     
    $row_table_activities = $reportInfo->getData();
    $rows_activities = $reportInfo->getAWTotal();
    $rows_total[] = array( 'total' => $reportInfo->getVolumeTotal());
    
     /* Build Data */
     
     $create['table'] = array(
      '#type' => 'table',
      '#header' => $headers,
      '#rows' => $row_table_activities,
      '#empty' => t('No entries available.'),
    ); 
     
     $create['table_act'] = array(
      '#type' => 'table',
      '#header' => $headers_total,
      '#rows' => $rows_activities,
      '#empty' => t('No entries available.'),
    ); 
     
     $create['table_total'] = array(
      '#type' => 'table',
      '#header' => $headers_grandTotal,
      '#rows' => $rows_total,
      '#empty' => t('No entries available.'),
    ); 
    
    $create['#cache']['max-age'] = 0;
    
    return $create; 
  }
  
  
}

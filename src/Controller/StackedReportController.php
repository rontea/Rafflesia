<?php


namespace Drupal\rafflesia\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\rafflesia\RafflesiaGetReport;
use Drupal\rafflesia\RafflesiaUserInformation;

class StackedReportController extends ControllerBase {
  
   /*
   * Build The Calendar JS
   **/
  
  public function build($from,$to){
    
    $dateFormat = 'Y-m-d';
    $timeFormat = 'g:i:s A';
    
    $userid = \Drupal::currentUser()->id();
    $report = new RafflesiaGetReport($from,$to);
    $report->setToTeam(1);
    $report->setUser($userid);
    $action = $report->generateAction();
    $bundle = $report->getTeamBundle();
     
    /* Get WSM of Manager */
    
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
     
     
     /* Build Data */
     $build['table_activities'] = array(
      '#type' => 'table',
      '#header' => $bundle['name'],
      '#rows' => $bundle['volume'],
      '#empty' => t('No entries available.'),
      );
    
    //Connect to Library
    $build['js_chart']['#attached']['library'][] = 'rafflesia/rafflesia_js_stacked_chart'; 
    
    
    /* Link Data of Drupal to JS 0.1 on rafflesia_js_chart.js */
    $build['name']['#attached']['drupalSettings']['rafflesia']['name'] = $bundle['name'];
   
    
    
    $labels = [];
    $data = [];
    
    $j = count($bundle['name']) - 1;
    $i = count($bundle['volume']);
    
    for($x = 0; $x < $i;$x++){
      
      $labels[$x] = $bundle['volume'][$x][0];
      
    }
    
    $temp = 0;
    
    for($y=0;$y < $j;$y++){
      for($z=0;$z < $i;$z++){
        
        if($z != 0){
          $data[$y][$temp] = $bundle['volume'][$y][$z];
          $temp++;
        }
        
      }
      $temp = 0;
    }
    
    $build['labels']['#attached']['drupalSettings']['rafflesia']['labels'] = $labels;
    $build['labels']['#attached']['drupalSettings']['rafflesia']['data'] = $data;
    
    $build['chart_twig'] = array(
      '#theme' => 'stackedChart',
      '#var' => 'Rafflesia Database',
      );
    
  $build['#cache']['max-age'] = 0; 
  
  return $build;
  }
  
}
